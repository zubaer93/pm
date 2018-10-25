<?php
/**
 * Created by PhpStorm.
 * User: Tonmoy
 * Date: 9/20/2018
 * Time: 6:43 PM
 */

namespace Api\Controller;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\Authentication;
use Stripe\Error\ApiConnection;
use Stripe\Error\Base;
use Stripe\Charge;
use Stripe\Plan;
use Stripe\Coupon;
use Stripe\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Core\Configure;


class SubscriptionController extends AppController
{

    public function initialize()
    {
        $this->loadModel('Api.Payments');
        $this->loadModel('Api.PaymentLog');
        $this->loadModel('Api.SubsctiptionProduct');
        Configure::load('Api.appConfig', 'default');
        parent::initialize();
        \Stripe\Stripe::setApiKey(Configure::read('stripe_test_secret'));
    }
// take plan_id and source as form data
    public function createWithStripe()
    {
        $user_id = $this->jwtPayload->id;
        $plan = $this->request->getData('plan_id');
        $source = $this->request->getData('source');

        $this->loadModel('Api.AppUsers');
        try {
            $stripe_data = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_data'])->first();
            if($stripe_data['stripe_data']){
                $data= json_decode($stripe_data['stripe_data']);
                $customer_stripe_id = $data->id;
            }
            else{

                $user = $this->AppUsers->find()->where(['id' => $user_id])->first();

                try{
                    $customer = \Stripe\Customer::create([
                        'email' => $user['email'],
                        'source' => $source,
                    ]);
                    $data['stripe_data'] = json_encode($customer);
                    $data['stripe_id'] = $customer['id'];
            
                    $user = $this->AppUsers->patchEntity($user, $data);
                    if ($this->AppUsers->save($user)) {
                        $customer_stripe_id = $customer['id'];
                    }
                    else{
                        $this->apiResponse = ['message' => 'Please try again '];
                        $this->httpStatusCode = 503;
                    } 
                } catch (\Stripe\Error\Base $e) {
                    $this->httpStatusCode = 400;
                    $this->apiResponse['error'] = $e->getMessage();
                    return;
                  }
            }

            try {
                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer_stripe_id,
                    'items' => [['plan' => $plan]],
                ]);
                $savePayment = $this->Payments->savePayments($this->jwtPayload->id, $subscription);
                $savePaymentLog = $this->PaymentLog->savePaymentLog($this->jwtPayload->id, $subscription);
                
                $getProduct = \Stripe\Product::retrieve($subscription['items']['data'][0]['plan']['product']);

                $user = $this->AppUsers->get($this->jwtPayload->id);

                $user->account_type = $getProduct['name'];
                if($this->AppUsers->save($user))
                {
                    $this->apiResponse = ['message' => 'You have been successfully Upgraded to the plan ' . $getProduct['name']];
                }
                else{
                    $this->apiResponse = ['message' => 'Please try again '];
                    $this->httpStatusCode = 503;
                }       
              } 
            catch (\Stripe\Error\Base $e) {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = $e->getMessage();
              }
        } catch (\Exception $e) {
            $this->apiResponse = $e->getMessage();
        }

    }

    //for creating product
    public function defineProduct($name){
        $product = \Stripe\Product::create([
            'name' => $name,
            'type' => 'service',
        ]);

        $this->loadModel('Api.SubscriptionProduct');
       
        $data['stripe_id'] = $product['id'];
        $data['name'] = $product['name'];
        $data['type'] = $product['type'];
        $data['created'] = $product['created'];
        $data['modified'] = $product['updated'];
        
        $save_prod = $this->SubscriptionProduct->newEntity();
        $save_prod = $this->SubscriptionProduct->patchEntity($save_prod, $data);

        if ($this->SubscriptionProduct->save($save_prod)) {
            $result = json_encode($product);
            $this->response->body($product);
            $this->response->type('json');
            return $this->response;        
        }
        
    }
    //form data product, name, interval, amount
    public function createPlan(){
        $requestData = $this->request->getData();
        
        $this->loadModel('Api.SubscriptionProduct');
        $this->loadModel('Api.SubscriptionPlan');

        $product = $this->SubscriptionProduct->find()->where(['name' => $requestData['product_name']])->first();
        $plan = \Stripe\Plan::create([
          'product' => $product['stripe_id'],
          'nickname' => $requestData['plan_name'],
          'interval' => $requestData['interval'],
          'currency' => 'usd',
          'amount' => $requestData['amount'],
        ]);
        $data['stripe_id'] = $plan['id'];
        $data['price'] = $plan['amount'];
        $data['product_id'] = $plan['product'];
        $data['name'] = $plan['nickname'];
        $data['interval_period'] = $plan['interval'];
        
        $save_plan = $this->SubscriptionPlan->newEntity();
        $save_plan = $this->SubscriptionPlan->patchEntity($save_plan, $data);
       
        if ($this->SubscriptionPlan->save($save_plan)) {
            $result = json_encode($plan);
            $this->response->body($plan);
            $this->response->type('json');
            return $this->response;
        }
    }

    //get all card info
    public function cardInfo(){
        $user_id = $this->jwtPayload->id;
        $this->loadModel('Api.AppUsers');

        $stripe_data = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_data'])->first();
        if($stripe_data['stripe_data']){
            $data= json_decode($stripe_data['stripe_data']);
            $customer_stripe_id = $data->id;
            try{
                $customer = \Stripe\Customer::retrieve($customer_stripe_id)
                ->sources->all(array('limit'=>10, 'object' => 'card'));
                
                $defaultCard = \Stripe\Customer::retrieve($customer_stripe_id)->default_source;

                $response = $customer['data'];
                
                foreach($response as $r){
                    if( $r['id'] == $defaultCard){
                        $r['isDefault'] = true;
                    }
                    else{
                        $r['isDefault'] = false;
                    }
                }
                $this->apiResponse['data'] = $response;
            }
            catch (\Stripe\Error\Base $e) {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = $e->getMessage();
              }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No card found for this user';
        }
       
    }

    //add another card
    public function addCard(){
        $source = $this->request->getData('source');
        $user_id = $this->jwtPayload->id;
        $this->loadModel('Api.AppUsers');

        $stripe_data = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_data'])->first();
        if($stripe_data['stripe_data']){
            $data= json_decode($stripe_data['stripe_data']);
            $customer_stripe_id = $data->id;
            try{
                $customer = \Stripe\Customer::retrieve($customer_stripe_id);
                $customer->sources->create(array("source" => $source));
                
                $this->apiResponse['message'] = "Card Successfully added";
            } 
            catch (\Stripe\Error\Base $e) {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = $e->getMessage();
              }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No card found for this user';
        }
       
    }

    //delete a card
    public function deleteCard(){
        $card_id = $this->request->getData('card_id');
        $user_id = $this->jwtPayload->id;
        $this->loadModel('Api.AppUsers');

        $stripe_data = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_data'])->first();
        if($stripe_data['stripe_data']){
            $data= json_decode($stripe_data['stripe_data']);
            $customer_stripe_id = $data->id;
            //dd($card_id);
            try{
                $customer = \Stripe\Customer::retrieve($customer_stripe_id);
                $customer->sources->retrieve($card_id)->delete();
                
                $this->apiResponse['message'] = "Card Successfully deleted";
            } 
            catch (\Stripe\Error\Base $e) {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = $e->getMessage();
              } 
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No card found for this card id';
        }
       
    }

    //change default card
    public function changeDefaultCard() {
        $card_id = $this->request->getData('card_id');
        $user_id = $this->jwtPayload->id;
        $this->loadModel('Api.AppUsers');

        $stripe_id = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_id'])->first();
        
        if($stripe_id['stripe_id']){
            $customer = \Stripe\Customer::retrieve($stripe_id['stripe_id']);
            $customer->default_source=$card_id;
            $customer->save();
           try{
                $this->apiResponse['message'] = "Default Card Successfully Updated";
            } 
           catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 400;
            $this->apiResponse['error'] = $e->getMessage();
          }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No existing card found for this user';
        }
       
    }

     //get customer stripe info
     public function customerInfo() {
        $user_id = $this->jwtPayload->id;
       
        $this->loadModel('Api.AppUsers');

        $stripe_id = $this->AppUsers->find()->where(['id' => $user_id])->select(['stripe_id'])->first();
        
        if($stripe_id['stripe_id']){
            $customer = \Stripe\Customer::retrieve($stripe_id['stripe_id']);
           
            try{
                $test = $customer;
                $this->apiResponse['data'] = $test;
            } 
            catch (\Stripe\Error\Base $e) {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = $e->getMessage();
              }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No stripe info found for this user';
        }
       
    }

    //get all product info
    public function getProducts(){
        try{
            $products = \Stripe\Product::all(array('limit'=>100));
            $test = $products['data'];
            $this->apiResponse['data'] = $test;
        } 
        catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 400;
            $this->apiResponse['error'] = $e->getMessage();
          }
    }

    //get all plan info
    public function getPlans(){
        try{
            $plan = \Stripe\Plan::all(array('limit'=>5));

            foreach($plan['data'] as $p){
                $product = \Stripe\Product::retrieve($p['product']);
                $p['product_name'] = $product['name'];
            }
            $test = $plan['data'];
            
            $this->apiResponse['data'] = $test;
        }
        catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 400;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    //webhook
    public function webhook(){
        $this->loadModel('Api.Webhook');
        $requestData = $this->request->getData();
        $data['response_data'] = json_encode($requestData);
        
        $save_plan = $this->Webhook->newEntity();
        $save_plan = $this->Webhook->patchEntity($save_plan, $data);       

        if ($this->Webhook->save($save_plan)) {
            $this->httpStatusCode = 200;
        }
        else{
            $this->httpStatusCode = 400;
        }
    }

    //charge created webhook for user payment history
    public function chargeSucceedWebhook(){
        $this->loadModel('Api.WebhookCharge');
        $this->loadModel('Api.StripeTransaction');
    
        $requestData = $this->request->getData();
       
        try{
            //checking the request is legit or not
            $test = \Stripe\Event::retrieve($requestData['id']);
            //
            $data['response_data'] = json_encode($requestData);
            $save_charge = $this->WebhookCharge->newEntity();
            $save_charge = $this->WebhookCharge->patchEntity($save_charge, $data);       
    
            if ($this->WebhookCharge->save($save_charge)) {
                $saveStripeTrans = $this->StripeTransaction->saveTransaction($requestData);
                $this->httpStatusCode = 200;
            }
            else{
                $this->httpStatusCode = 400;
            }
        }
        catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    //webhook when charge failed
    public function chargeFailedWebhook() {
        $this->loadModel('Api.WebhookCharge');
        
        $requestData = $this->request->getData();
       
        try{
            //checking the request is legit or not
            //$test = \Stripe\Event::retrieve($requestData['id']);
            //
            if($requestData['type'] == "charge.failed"){
                dd($requestData['type']);
            }
            $data['response_data'] = json_encode($requestData);
            $save_charge = $this->WebhookCharge->newEntity();
            $save_charge = $this->WebhookCharge->patchEntity($save_charge, $data);       
    
            if ($this->WebhookCharge->save($save_charge)) {
                $saveStripeTrans = $this->StripeTransaction->saveTransaction($requestData);
                $this->httpStatusCode = 200;
            }
            else{
                $this->httpStatusCode = 400;
            }
        }
        catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    //get payment history
    public function paymentHistory(){
        $this->loadModel('Api.StripeTransaction');
        $this->loadModel('Api.AppUsers');
        
        $user = $this->AppUsers->find()->where(['id' => $this->jwtPayload->id])->select(['stripe_id', 'email'])->first();
        
        $paymentHistory = $this->StripeTransaction->find()
            ->where(['customer' => $user['stripe_id']])
            ->order(['created' => 'DESC'])
            ->toArray();
        if ($paymentHistory) {
            $this->apiResponse['data'] = $paymentHistory;
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = "No payment history found";
        }
    }

    //get invoice 
    public function getInvoice($invoice_id){
        try{
            $invoice = \Stripe\Invoice::retrieve($invoice_id);
            
            $invoiceLine = \Stripe\Invoice::retrieve($invoice_id)->lines->all(array(
                'limit'=>5));
            $test = $invoice;
            $test['product'] = $invoiceLine['data'][0];
            
            $this->apiResponse['data'] = $test;
        }
        catch (\Stripe\Error\Base $e) {
            $this->httpStatusCode = 400;
            $this->apiResponse['error'] = $e->getMessage();
            }
        
    }

    //webhook data customize
    // public function webhookCustomize(){
    //     $this->loadModel('Api.Webhook');
    //     $requestData = $this->request->getData();
    //    // $datas = $this->Webhook->find()->where(['id' => 48])->first();
    //     //$datas = $this->Webhook->find()->where(['id' => 51])->first();
    //     //$requestData=json_decode($datas['response_data']);
    //     dd($requestData);
    //     // $invoice = \Stripe\Invoice::retrieve("in_1DLkw5Bwzdqmhm0taArwpgQ5");
    //     // dd($invoice);
   
    // }


}