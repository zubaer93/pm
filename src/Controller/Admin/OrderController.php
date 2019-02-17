<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Admin;

use App\Controller\AppController;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class OrderController extends AppController
{
    private $approve = 2;
    private $reject = 3;

  public function index(){

  }

  public function ajaxOrderSearch()
  {
      $requestData = $this->request->getQuery();

      $obj = new \App\Model\DataTable\OrderAdminDataTable();
      $result = $obj->ajaxOrderSearch($requestData);

      echo $result;
      exit;
  }

  public function approve ($id)
  {
      $status = $this->approve;
      $this->loadModel('Transactions');
      $result = $this->Transactions->adminOrderTransaction($id,$status);
      if($result){
          $this->Flash->success(__('Order is approve!'),[
              'clear'=> true
          ]);
      }else{
          $this->Flash->error(__('The order was not approved. Please, try again.'));
      }
      $this->redirect(['_name'=>'order_list']);
  }
  public function reject ($id)
  {
      $status = $this->reject;
      $this->loadModel('Transactions');
      $result =$this->Transactions->adminOrderTransaction($id,$status);
      if($result){
          $this->Flash->success(__('Order is rejected!'));
      }else{
          $this->Flash->error(__('The order was not rejected. Please, try again.'));
      }
      $this->redirect(['_name'=>'order_list']);
  }
}