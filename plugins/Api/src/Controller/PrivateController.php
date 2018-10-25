<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class PrivateController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }
    /*this function will fetch all private message for an user
     *
     * */
    public function index()
    {
        $this->loadModel('Api.Private');
        $data = $this->request->getQueryParams();
        $response = $this->filter($data);
        $this->paginate = [
            'fields' => ['id','user_id','name','slug','created_at','user' => 'CONCAT("",Users.first_name,"",Users.last_name,"")'],
            'contain' => ['Users'],
            'limit' => 10,
        ];
        if (!empty($data)) {
            $this->paginate['conditions'] = $response[0];
            $this->paginate['order'] = [$response[2] => $response[1]];
        } else {
            $this->paginate['order'] = ['Private.created_at' => 'DESC'];
        }
        $posts= $this->paginate($this->Private->find()->where(['user_id' => $this->jwtPayload->id]))->toArray();
        if (!empty($posts)) {
            $this->apiResponse['data'] =$posts;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Private'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Private'];
        }
    }

    /*this function will add private post
     *
     * */
    public function privateSave()
    {
        $this->loadModel('Api.AppUsers');
        $this->loadModel('Api.Notifications');
        $this->loadModel('Api.Private');
        $this->loadModel('Api.UserInvite');
        if (isset($this->request) && $this->request->allowMethod(['post'])) {
            try{
                $result = $this->Private->setPrivate($this, $this->jwtPayload->id);
                if ($result['result']) {
                    $data = ['id' => $result['id'], 'user_id' => $this->request->getData('private_user')];
                    //$invite = $this->UserInvite->setUserInvite($data);
                    $privateGet = $this->Private->get($result['id']);
                    foreach ($this->request->getData('private_user') as $userId) {
                        $referer = $this->currentLanguage;
                        $url = $this->_getUrl();
                        $url = parse_url($url, PHP_URL_HOST);
                        $url = '/' . $referer . '/private/' . $privateGet->slug;
                        $this->Notifications->user_id = $userId;
                        $this->Notifications->url = $url;
                        $this->Notifications->title = $this->jwtPayload->username . ' - ' . implode(' ', array_slice(explode(' ', "Private $privateGet->name "), 0, 5)) . '...';
                        $bool = $this->Notifications->setNotification($this->Notifications);
                    }
                }
                $this->apiResponse['message'] = 'Private post added successfully.';
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
    }

    public function ajaxManagePrivateFrontSearch()
    {
        $id = $this->jwtPayload->id;
        $requestData = $this->request->getQuery();
        $obj = new \Api\Model\DataTable\PrivateDataTable();
        $result = $obj->ajaxManagePrivateFrontSearch($requestData, $id);
        echo $result;
        exit;
    }

    public function privateSearch()
    {
        $id = $this->jwtPayload->id;
        $requestData = $this->request->getQuery();
      
        $Private = TableRegistry::get('Api.Private');
        $private = $Private->find()->where(['user_id'=>$id])->contain([
            'UserInvite'=>function ($q)
            {
                return $q->autoFields(false)
                    ->contain(['AppUsers'=>function ($q){
                        return $q->autoFields(false)
                            ->select('AppUsers.username');
                    }
                    ]);
            },
        ]);
        $detail = $private;
        $count = $private->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        if (isset($requestData['search_value']) && !empty($requestData['search_value'])) {
            $search = $requestData['search_value'];
            $detail = $detail
                ->where([
                    'OR' => [
                        ['(Private.name) LIKE' => '%' . $search . '%']
                    ]]);
            $news_count = $detail;
            $totalFiltered = $news_count->count();
        }

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );

        $order_col = isset($requestData['order_col']) ? $requestData['order_col'] : 0;
        $sidx = isset($columns[$order_col]) ? $columns[$order_col] : $columns[0];

        $sord = !isset($requestData['order_dir']) ? 'asc' : $requestData['order_dir'];
        $start = 0;
        $length = !isset($requestData['length']) ? 5 : $requestData['length'];
        $page = isset($requestData['page']) ? $requestData['page'] : 1;

        $results = $detail
            ->order($sidx . ' ' . $sord)
            ->limit((int)$length)
            ->page($page);
            
        $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['Private'];
        $data = array();
       
        foreach ($results as $row) {
            $userName = '';
            $i = 0;
            
            foreach ($row['user_invite'] as $appuser) {
                if ($i < 4) {
                $userName .= $appuser['app_user']['username'] . ',';
                }else{
                        break;
                }
                $i++;
            }
            $nestedData = [];
            $nestedData['name'] = $row["name"];
            $nestedData['user_name'] =  $userName;
            $nestedData['created_at'] = $row["created_at"];
            $data[] = $nestedData;
        }
        $json_data = array(
            "data" => $data,
            "paginate" => $paginate
        );
        $this->apiResponse = $json_data;
    }

    /*this function will update private post
     *
     * @param post id
     * */
    public function editPost($message_id = null)
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Private'));
        $data = $this->request->getData();
        if (!empty($data) && !empty($message_id)) {
            $result = $this->Private->get($message_id);
            if(!empty($result)){
                $result = $this->Private->patchEntity($result, $data);
                if ($this->Private->save($result)) {
                    $this->apiResponse['data'] = $result;
                    $this->apiResponse['message'] = 'Post has been updated successfully.';
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['message'] = 'Post could not be updated. Please try again.';
                }
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter private post id.';
        }
    }

    /*delete a message
     * @param message id
     * */
    public function privateDeleteRoom($message_id = null)
    {
        $this->request->allowMethod('delete');
        $this->add_model(array('Api.Private'));
        if (!empty($message_id)) {
            $entity = $this->Private->get($message_id);
            if(!empty($entity)){
               if($this->Private->delete($entity)){
                   $this->apiResponse['message'] = 'Private post has been deleted successfully.';
               } else {
                   $this->httpStatusCode = 403;
                   $this->apiResponse['error'] = 'Private post could not be deleted. Please try again.';
               }
            } else {
                throw new NotFoundException();
            }
        } else{
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide private post id';
        }
    }

    protected function authUser()
    {
        if (!$this->Auth->user()) {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Not logged in'
            ];
            $this->response->statusCode(401);
            $this->setJsonResponse($response);
            return $this->response;
        }
    }

     /* filter and sorting all private post
     * */
    public function filter($data = array())
    {
        $this->add_model(array('Private'));
        $conditions = [];
        $sort_order = 'desc';
        $sort = 'Private.created_at';
        if (!empty($data['sort_by'])) {
            switch ($data['sort_by']) {
                case 'name':
                    $sort = 'Private.name';
                    break;
                case 'user':
                    $sort = 'Private.user';
                    break;
                default:
                    $sort = 'Private.created_at';
            }
            $sort_order = $data['sort_order'];
        }
        if (!empty($data['query'])) {
            if (!empty($data['query']['name'])) {
                $conditions = array_merge(['Private.name LIKE' => '%' . $data['query']['name'] . '%', $conditions]);
            }
            if (!empty($data['query']['created_at'])) {
                $date = date("Y-m-d", strtotime($data['query']['created_at']));
                $conditions = array_merge(['Private.created_at LIKE' => '%' . $date . '%', $conditions]);
            }
        }
        return array($conditions, $sort_order, $sort);
    }

}
