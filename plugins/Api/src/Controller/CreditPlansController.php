<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class CreditPlansController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->add_model(array('Api.CreditPlans', 'Api.AppUsers', 'Api.FundRequest'));


    }
//add a plan. only for admin
    public function add(){
        $this->request->allowMethod('post');
        $data = $this->request->getData();
        if($this->jwtPayload->role == 'admin'){
            try {
                $data['created_by'] = $this->jwtPayload->id;
                $credit_plans = $this->CreditPlans->newEntity();
                $credit_plans->plan_name = $data['plan_name'];
                $credit_plans = $this->CreditPlans->patchEntity($credit_plans, $data);
                $save = $this->CreditPlans->save($credit_plans);
                if ($save) {
                    $this->apiResponse['message'] = 'Plan has been saved successfully.';
                    $this->apiResponse['data'] = $save;
                } else {
                    $this->httpStatusCode = 500;// bad request
                    $this->apiResponse['error'] = 'Plan could not be saved. Please try again.';
                }
               
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
        else {
            $this->httpStatusCode = 500;// bad request
            $this->apiResponse['error'] = 'you are not authorized';
        }
        
    }

    public function getPlans() {
        $this->request->allowMethod('get');
        $user = $this->AppUsers->get($this->jwtPayload->id);
        if($user['role'] == 'user'){
            if( $user['account_type'] == 'verified'){
                $credit_plans = $this->CreditPlans->find()
                ->where(['eligible_catagory <=' => $user['user_level']])
                ->toArray();
                if ($credit_plans) {
                    $this->apiResponse['data'] = $credit_plans;
                } else {
                    $this->httpStatusCode = 404;// bad request
                    $this->apiResponse['error'] = 'No credit plans found for this user';
                }
            }
            else{
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'Please verify your account';
            }                 
        }
        elseif($user['role'] == 'admin'){
            $credit_plans = $this->CreditPlans->find('all');
            if ($credit_plans) {
                $this->apiResponse['data'] = $credit_plans;
            } else {
                $this->httpStatusCode = 404;// bad request
                $this->apiResponse['error'] = 'No credit plans';
            }
        }
    }

    public function fundRequest(){
        $this->request->allowMethod('post');
        $data = $this->request->getData();
        if($data){
            $user = $this->AppUsers->get($this->jwtPayload->id);
            if( $user['account_type'] == 'verified'){
                $request = $this->FundRequest->newEntity();
                //$data['plan_id'] = $plan_id;
                $data['user_id'] = $this->jwtPayload->id;
                $data['status'] = 0;
                $data['request_date'] = date('Y-m-d H:i:s');
    
                $request = $this->FundRequest->patchEntity($request, $data);
                $save = $this->FundRequest->save($request);
                if ($save) {
                    $this->apiResponse['message'] = 'Your fund request succesfully created. You will receive fund within 30 minutes';
                    $this->apiResponse['data'] = $save;
                } else {
                    $this->httpStatusCode = 500;// bad request
                    $this->apiResponse['error'] = 'Your request could not be proceed. Please try again.';
                }
            }
            else{
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'Please verify your account';
            }
           
        }
        else{
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'please insert data';
        }
    }
    //get all request
    public function getFundRequestAll(){
        $this->request->allowMethod('get');
     
        $request = $this->FundRequest->find()
                    ->where(['FundRequest.user_id' =>$this->jwtPayload->id])
                    ->contain('CreditPlans')
                    ->toArray();
    
        if ($request) {
            $this->apiResponse['data'] = $request;
        } else {
            $this->httpStatusCode = 500;// bad request
            $this->apiResponse['error'] = 'No fund request found';
        }
    } 
    //get specific request
    public function getFundRequest(){
        $this->request->allowMethod('get');
        $request_id = $this->request->getData('request_id');
        if($request_id == null){
            $request = $this->FundRequest->find()
                        ->where(['user_id' =>$this->jwtPayload->id])
                        ->toArray();
        }
        else{
            $request = $this->FundRequest->find()
                        ->where(['FundRequest.id' =>$request_id])
                        ->where(['FundRequest.user_id' =>$this->jwtPayload->id])
                        ->contain('CreditPlans')
                        ->first();
        }
        if ($request) {
            $this->apiResponse['data'] = $request;
        } else {
            $this->httpStatusCode = 500;// bad request
            $this->apiResponse['error'] = 'No fund request found';
        }
    }   

    public function deleteFundRequest()
    {
        $this->request->allowMethod('delete');
        $request_id = $this->request->getData('request_id');
        if (!empty($request_id)) {
            try {
                $request = $this->FundRequest->get($request_id);
                if($request['status'] == 0){
                    if ($this->FundRequest->delete($request)) {
                        $this->apiResponse['data'] = $request;
                        $this->apiResponse['message'] = 'Request has been deleted successfully.';
                    } else {
                        $this->httpStatusCode = 404;
                        $this->apiResponse['error'] = 'Request could not be deleted. Please try again.';
                    }
                }
                else{
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'Cannot delete request. Admin already approed your request';
                }
                
            } catch (\Exception $e) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = $e->getMessage();;
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter request id';
        }
        
    }

    public function refund($request_id){
        $this->request->allowMethod('post');
        if (!empty($request_id)) {
            try {
                $refund = $this->FundRequest->get($request_id);
                if($refund){
                    $data = $this->request->getData('refund_transaction_id');
                    $refund->refund_transaction_id = $data;
                    $refund->refund_status = 2;
                    $save = $this->FundRequest->save($refund);
                    if ($save) {
                        $this->apiResponse['message'] = 'Your refund request accepted';
                        $this->apiResponse['data'] = $save;
                    } else {
                        $this->httpStatusCode = 500;// bad request
                        $this->apiResponse['error'] = 'Your request could not be proceed. Please try again.';
                    }
                    }
                else{
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'No request has found with this id';  
                }
            }
            catch (\Exception $e) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = $e->getMessage();;
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter request id';
        }
    }

    //admin stuffs
    //approve a fund request
    public function approveRequest($request_id){
        $this->request->allowMethod('post');
        $data = $this->request->getData();
        if($this->jwtPayload->role == 'user'){
        //if($this->jwtPayload->role == 'admin'){
            try {
                
                $request = $this->FundRequest->find()
                        ->where(['FundRequest.id' => $request_id])
                        ->contain('CreditPlans')
                        ->first();
                //dd($request['credit_plan']['period']);
                if($data['status'] == 1){
                    $transfer_date = date('Y-m-d H:i:s');
                    $refund_period = $request['credit_plan']['period'];
                    $refund_date = date('Y-m-d H:i:s', strtotime("+".$refund_period." days"));
                
                    $data['transfer_date'] = $transfer_date;
                    $data['refund_date'] = $refund_date;
                
                    // $data['transfer_date'] = ;
                    // $data['refund_date'] = ;
                }
                $data['created_by'] = $this->jwtPayload->id;
                $credit_plans = $this->CreditPlans->newEntity();
                $credit_plans->plan_name = $data['plan_name'];
                $credit_plans = $this->CreditPlans->patchEntity($credit_plans, $data);
                $save = $this->CreditPlans->save($credit_plans);
                if ($save) {
                    $this->apiResponse['message'] = 'Plan has been saved successfully.';
                    $this->apiResponse['data'] = $save;
                } else {
                    $this->httpStatusCode = 500;// bad request
                    $this->apiResponse['error'] = 'Plan could not be saved. Please try again.';
                }
               
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
        else {
            $this->httpStatusCode = 500;// bad request
            $this->apiResponse['error'] = 'you are not authorized';
        }
    }
}