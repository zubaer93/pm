<?php

namespace Api\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use App\Model\Service\Currency;
use Cake\Utility\Inflector;
use CakeDC\Users\Controller\Component\UsersAuthComponent;

class FollowersController extends AppController
{
    protected $_companies;
    protected $_currency;
    protected $_transactions;

    const JMD = 'JMD';
    const USD = 'USD';


    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }
    //list of all followers
    public function followersList(){
        $userId = $this->jwtPayload->id;

        $this->loadModel('Api.Follow');
        $this->paginate = [
            'limit' => 10,
            'order' => ['Follow.created_at' => 'DESC']
        ];
        $followers =$this->paginate($this->Follow->find('all')
            ->contain('Follower')
            ->where(['Follow.follow_user_id' => $userId])
            ->order(['Follow.created_at' => 'desc']))
            ->toArray();
      
        if (!empty($followers)) {
            $this->apiResponse['data'] = $followers;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Follow'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Follow'];
        }
    }
    //list of all followings
    public function followingList(){
        $userId = $this->jwtPayload->id;

        $this->loadModel('Api.Follow');
        $following = $this->Follow->find('all')
            ->contain('Following')
            ->where(['Follow.user_id' => $userId])
            ->order(['Follow.created_at' => 'desc'])
            ->toArray();
      
        if (!empty($following)) {
            $this->apiResponse['data'] = $following;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Follow'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Follow'];
        }
    }

    //list of followers and followings
    public function allList(){
        $userId = $this->jwtPayload->id;

        $this->loadModel('Api.Follow');
        $following = $this->Follow->find('all')
            ->contain('Following')
            ->where(['Follow.user_id' => $userId])
            ->select(['following.id', 'following.username', 'following.first_name', 'following.last_name'])
            ->order(['Follow.created_at' => 'desc'])
            ->toArray();
            
        $followers =$this->Follow->find('all')
        ->contain('Follower')
        ->where(['Follow.follow_user_id' => $userId])
        ->select(['follower.id', 'follower.username', 'follower.first_name', 'follower.last_name'])
        ->order(['Follow.created_at' => 'desc'])
        ->toArray();

        $data = array();
        
        foreach($followers as $follow){
            $nestedData = [];
            $nestedData['id'] = $follow['follower']['id'];
            $nestedData['username'] = $follow['follower']['username'];
            $nestedData['name'] = $follow['follower']['first_name'].' '.$follow['follower']['last_name'];

            $data[] = $nestedData;
        }
        foreach($following as $follow){
            $nestedData = [];
            $nestedData['id'] = $follow['following']['id'];
            $nestedData['username'] = $follow['following']['username'];
            $nestedData['name'] = $follow['following']['first_name'].' '.$follow['following']['last_name'];

            $data[] = $nestedData;
        }
        
        if ($data) {
            $this->apiResponse['data'] = $data;
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No user found with this id';
        }
    }
    //follow an user
    public function follow($user_id)
    {
        
        $this->loadModel('Api.AppUsers');
        $userDetails = $this->AppUsers->find()->where(['id' => $user_id])->first();
       
        if (!is_null($userDetails)) {
            $this->loadModel('Api.Follow');
            $result = $this->Follow->setOrUpdate($userDetails['id'], $this->jwtPayload->id);
            if ($result) {
                $this->apiResponse['message'] = 'Successfully followed user.';
            } else {
                $this->apiResponse['error'] = 'Please try again.';
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No user found with this id';
        }
    }

    //unfollow an user
    public function unfollow($user_id)
    { 
        $Id = $this->jwtPayload->id;

        $this->loadModel('Api.Follow');
        $unfollow = $this->Follow->find()
            ->where(['Follow.user_id' => $Id])
            ->where(['Follow.follow_user_id' => $user_id])
            ->first();
        if($unfollow){
            if ($this->Follow->delete($unfollow)) {
                $this->apiResponse['message'] = 'Unfollowed succesfully.';
            }
            else{
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'Try again';
            }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No such user found in follow list';
        }
    }
}