<?php

namespace Api\Controller;

use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;

class MessagesController extends AppController
{

    /* this function will fetch all the message for a user
    *
    * @param user id from token
    * */
    public function index()
    {
        $this->request->allowMethod('GET');
        $this->add_model(array('Api.Messages', 'Api.AppUsers'));
        try {
            $messages_data = $this->getAllMessages();
            $messages = $this->Messages->getFeedData($messages_data);
            if (!empty($messages)) {
                $this->apiResponse['data'] = $messages;
            } else {
                $this->apiResponse['data'] = [];
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    /* this function will fetch all the comments of a message  for a user
    *
    * @param user_id from token, message_id
    * */
    public function view($message_id = null)
    {
        $this->request->allowMethod('GET');
        $this->add_model(array('Api.Messages', 'Api.AppUsers'));
        if (!empty($message_id)) {
            try {
                $user_id = $this->jwtPayload->id;
                $messages_data = $this->Messages->find()
                    ->where(['Messages.user_id' => $user_id, 'Messages.comment_id' => $message_id])
                    ->contain(['AppUsers'])
                    ->order(['Messages.created' => 'desc'])
                    ->toArray();
                $messages = $this->Messages->getCommentData($messages_data);
                if (!empty($messages)) {
                    $this->apiResponse['data'] = $messages;
                } else {
                    $this->apiResponse['data'] = [];
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter message id.';
        }
    }

    /* this function will add message(share an idea for front section)
     *
     * @get user id from token
     * */
    public function add()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Messages'));
        $requestData = $this->request->getData();
        if (!empty($requestData)) {
            try {
                $requestData['user_id'] = $this->jwtPayload->id;
                $requestData['market'] = $this->currentLanguage;
                $message = $this->Messages->newEntity();
                $message = $this->Messages->patchEntity($message, $requestData);
                if ($this->Messages->save($message)) {
                    $this->saveScreenShot($message);
                    $this->sendNotifications($message);
                    if(!empty($requestData['file_content'])){
                        foreach ($_FILES as $file){
                            if(empty($data['page'])){
                                $res = $this->upMulFile($file);
                            } else {
                                $res = $this->upMulFile($file, $data['page']);
                            }
                        }
                    }
                    $this->apiResponse['message'] = 'Message has been saved successfully.';
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'Message could not be saved. Please try again.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }

        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide message data';
        }
    }

    /* this function will add comment on a message based on message id
     *
     * @get user_id from token, comment_id as form data
     * */
    public function addComment()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Messages', 'Api.AppUsers'));
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                $data['user_id'] = $this->jwtPayload->id;
                $data['market'] = $this->currentLanguage;
                if (!empty($data['message_id'])) {
                    $data['comment_id'] = $data['message_id'];
                }
                $message = $this->Messages->newEntity();
                $message = $this->Messages->patchEntity($message, $data);
                if ($this->Messages->save($message)) {
                    $this->apiResponse['message'] = 'Comment has been saved successfully.';
                    $messages_data = $this->Messages->find()->where(['Messages.user_id' => $message['user_id'], 'Messages.id' => $message['id']])->contain(['AppUsers'])->first();
                    $messages = $this->Messages->getFeedData2($messages_data);
                    $this->saveScreenShot($message);
                    $this->sendNotifications($message);
                    $this->apiResponse['data'] = $messages;
                    return;
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'Comment could not be saved. Please try again.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide comments data';
        }

    }

    /* this function will add rating on a message's comments
    *
    * @get user_id from token, message_id, grade as form data
    * */
    public function addRating()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Ratings'));
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                $data['user_id'] = $this->jwtPayload->id;
                $user = $this->Ratings->find()->where(['user_id' => $data['user_id'], 'message_id' => $data['message_id']])->first();
                if($user){
                    $rating = $this->Ratings->patchEntity($user, $data);
                }
                else{
                    $rating = $this->Ratings->newEntity();
                    $rating = $this->Ratings->patchEntity($rating, $data);
                }
                if ($this->Ratings->save($rating)) {
                    $this->apiResponse['message'] = 'Rating has been saved successfully.';
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'Rating could not be saved. Please try again.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide ratings data';
        }
    }

    /* this function will delete a message or a comments
    *
    * @message_ids
    * */
    public function delete($message_id = null)
    {
        $this->request->allowMethod('delete');
        $this->add_model(array('Api.Messages'));
        if (!empty($message_id)) {
            try {
                $message = $this->Messages->get($message_id);
                if (!empty($message)) {
                    if ($this->Messages->delete($message)) {
                        $this->apiResponse['message'] = 'Comment has been deleted successfully.';
                    } else {
                        $this->httpStatusCode = 403;
                        $this->apiResponse['error'] = 'Comment could not be deleted. Please try again.';
                    }
                } else {
                    throw new NotFoundException();
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide message id.';
        }
    }

    /**
     * share() method will save share message
     *
     * @param $parent_id
     * @return void
     */
    public function share($parent_id)
    {

        $this->add_model(['Api.Messages']);
        $message = $this->Messages->newEntity();
        $this->request->allowMethod(['post']);

        $message->message = $this->request->getData('message');
        $message->user_id = $this->jwtPayload->id;
        $message->parent_id = $parent_id;
        $message->company_id = $this->request->getData('company_id');
        $message->trader_id = $this->request->getData('trader_id');
        $message->country_id = $this->request->getData('market');

        try {
            if (!$this->Messages->save($message)) {
                $response['error'] = 'Message can not  be saved';
            } else {
                $this->saveScreenShot($message);
                $this->sendNotifications($message);
                $response['message'] = 'Message Shared Successfully';
            }
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        $this->apiResponse = $response;
    }

    /**
     * sendNotifications method will be send to those people who were noted in the message
     *
     * @param $message object
     * @return mixed
     */
    private function saveScreenShot($message)
    {
        $matches = [];
        preg_match_all('@((https?://)?([-\\w]+\\.[-\\w\\.]+)+\\w(:\\d+)?(/([-\\w/_\\.]*(\\?\\S+)?)?)*)@', $message->message, $matches);
        $data_img = null;
        $url = null;
        if (isset($matches[0]) && count($matches[0])) {
            $data_img = $this->getScreenshot(end($matches[0]));
            $url = end($matches[0]);
            $this->loadModel('Api.ScreenshotMessage');
            $screenshot_message = $this->ScreenshotMessage->newEntity();
            $screenshot_message->message_id = $message['id'];
            $screenshot_message->data = $data_img;
            $screenshot_message->url = $url;
            $this->ScreenshotMessage->save($screenshot_message);
            $matches = null;
        }
    }

    private function sendNotifications($message)
    {
        $this->loadModel('Api.AppUsers');
        $matches = [];
        preg_match_all('/@(\w+)/', $message->message, $matches);
        if (!empty($matches[1])) {

            $this->loadModel('Api.Notifications');
            try {
                foreach ($matches[1] as $match) {
                    $userData = $this->AppUsers->getUserInfo($match);

                    if (!is_null($userData)) {
                        $this->Notifications->user_id = $userData->id;
                        $this->Notifications->url = $this->referer();
                        $this->Notifications->title = $this->jwtPayload->username . ' - ' . implode(' ', array_slice(explode(' ', $message->message), 0, 5)) . '...';
                        $bool = $this->Notifications->setNotification($this->Notifications);
                        $usersEmail = new \App\Mailer\UsersMailer();
                        try {
                            $email = new Email();
                            $email
                                ->setTemplate('sendNotification')
                                ->setEmailFormat('html')
                                ->setTo($userData['email'])
                                ->setFrom('app@domain.com')
                                ->setSubject('Someone noticed you in a post')
                                ->setViewVars(['email' => $userData['email'],
                                    'from_username' => $this->jwtPayload->username,
                                    'to_username' => $userData['username'],
                                    'to_first_name' => $userData['first_name'],
                                    'to_last_name' => $userData['last_name'],
                                    'url' => $this->referer(),
                                    'message' => implode(' ', array_slice(explode(' ', $message->message), 0, 5)),
                                ])
                                ->send();
                        } catch (\Exception $e) {

                        }
                    }
                }
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @param $src
     * @return mixed
     */

    public function getScreenshot($src)
    {
        $siteURL = trim($src);
        $googlePagespeedData = @file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true&strategy=desktop");

        $googlePagespeedData = json_decode($googlePagespeedData, true);
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_', '-'), array('/', '+'), $screenshot);
        return $screenshot;
    }

    /**
     * @param null $comment_id
     * @return array
     */
    private function getAllMessages($comment_id = null)
    {
        $requestData = $this->request->getQuery();
        $messages = [];
        if (!isset($requestData['page'])) {
            $requestData['page'] = 'all';
        }
        if (is_null($comment_id)) {
            $where = ['Messages.comment_id IS' => NULL];
        } else {
            $where = ['Messages.comment_id IN' => $comment_id];
        }
        switch ($requestData['page']) {
            case 'all':
                {
                    $messages = $this->Messages->find('all')
                        ->where(['country_id' => $this->currentLanguage])
                        ->where(['Messages.private_id IS' => NULL])
                        ->where($where)
                        ->contain('AppUsers')
                        ->contain(['Ratings'])
                        ->contain(['ScreenshotMessage'])
                        ->order(['Messages.created' => 'desc'])
                        ->toArray();
                    break;
                }
            case 'company':
                {
                    $this->loadModel('Api.Companies');
                    $symbol = $this->Companies->get($requestData['data']);
                    $messages = $this->Messages->find()
                        ->where([
                            'OR' => [
                                ['company_id' => $requestData['data']],
                                ['BINARY (message) LIKE' => '%' . $symbol['symbol'] . '%']
                            ]
                        ])
                        ->where($where)
                        ->where(['Messages.private_id IS' => NULL])
                        ->contain('AppUsers')
                        ->contain(['Ratings'])
                        ->contain(['ScreenshotMessage'])
                        ->order(['Messages.created' => 'desc'])
                        ->toArray();
                    break;
                }
            case 'trader':
                {
                    $this->loadModel('Api.Trader');
                    $exchangeInfo = $this->Trader->__getTraderInfoFromCurrency($requestData['data']);
                    $messages = $this->Messages->find('all')
                        ->where([
                            'OR' => [
                                ['trader_id' => $exchangeInfo['id']],
                                ['message LIKE' => '% ' . $exchangeInfo['from_currency_code'] . ' %'],
                                ['message LIKE' => '% ' . $exchangeInfo['to_currency_code'] . ' %'],
                                ['message LIKE' => '% ' . $exchangeInfo['from_currency_name'] . ' %'],
                                ['message LIKE' => '% ' . $exchangeInfo['to_currency_name'] . ' %'],
                            ]
                        ])
                        ->where($where)
                        ->where(['Messages.private_id IS' => NULL])
                        ->contain('AppUsers')
                        ->contain(['Ratings'])
                        ->contain(['ScreenshotMessage'])
                        ->order(['Messages.created' => 'desc'])
                        ->toArray();
                    break;
                }
            case 'user':
                {
                    $userId = $this->jwtPayload->id;
                    $messages = $this->Messages->find('all')
                        ->where(['user_id' => $userId])
                        ->where(['Messages.private_id IS' => NULL])
                        ->where($where)
                        ->contain('AppUsers')
                        ->contain(['Ratings'])
                        ->contain(['ScreenshotMessage'])
                        ->order(['Messages.created' => 'desc'])
                        ->toArray();
                    break;
                }
            case 'private':
                {
                    $private_id = $requestData['data'];
                    $messages = $this->Messages->find('all')
                        ->where($where)
                        ->where(['Messages.country_id' => $this->currentLanguage])
                        ->where(['Messages.private_id' => $private_id])
                        ->order(['Messages.modified DESC'])
                        ->contain('AppUsers')
                        ->contain(['Ratings'])
                        ->contain(['ScreenshotMessage'])
                        ->toArray();
                    break;
                }
        }

        return $messages;
    }

      /**
     * postModal() method will save share message
     *
     * @return json
     */
    public function postModal($parent_id)
    {
        $this->request->allowMethod('post');
        if ($this->jwtPayload->id) {    
            $this->add_model(array('Api.Messages'));
            $message = $this->Messages->newEntity();

            $message->message = $this->request->getData('message');
            $message->user_id = $this->jwtPayload->id;
            $message->parent_id = $parent_id;
            $message->company_id = $this->request->getData('company_id');
            $message->trader_id = $this->request->getData('trader_id');
            $message->country_id = $this->request->getData('market');
            if (!$this->Messages->save($message)) {
                $response = null;
            } else {
                $matches = [];
                preg_match_all('@((https?://)?([-\\w]+\\.[-\\w\\.]+)+\\w(:\\d+)?(/([-\\w/_\\.]*(\\?\\S+)?)?)*)@', $message->message, $matches);
                $data_img = null;
                $url = null;
                if (isset($matches[0]) && count($matches[0])) {
                    $data_img = $this->getScreenshot(end($matches[0]));
                    $url = end($matches[0]);
                    $this->loadModel('ScreenshotMessage');
                    $screenshot_message = $this->ScreenshotMessage->newEntity();
                    $screenshot_message->message_id = $message['id'];
                    $screenshot_message->data = $data_img;
                    $screenshot_message->url = $url;
                    $this->ScreenshotMessage->save($screenshot_message);
                    $matches = null;
                }
                $MessageData = $this->Messages->getMessage($parent_id);
                $this->loadModel('Api.AppUsers');
                $UserData = $this->AppUsers->find()->where(['id' => $this->jwtPayload->id])->first();
                $ScreenshotMessage = TableRegistry::get('ScreenshotMessage');
                $data_parent = $ScreenshotMessage->getMessageData($parent_id);

                if (is_null($data_parent)) {
                    $data_img_parent = null;
                    $data_url_parent = null;
                } else {
                    $data_img_parent = $data_parent['data'];
                    $data_url_parent = $data_parent['url'];
                }
                $response = [
                    'data' => $this->Messages->getFeedData($message, $UserData),
                    'img_page' => $data_img,
                    'url_page' => $url,
                    'img_data_parent' => $data_img_parent,
                    'url_data_parent' => $data_url_parent,
                    'parent' => $this->Messages->getFeedData($MessageData, $UserData),
                    'parent_avatar' => $this->request->getData('parent_img_url'),
                ];

                $this->sendNotifications($message);
            }
            if ($response) {
                $this->apiResponse['data'] = $response;
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'No data has found.';
            }
        }
        else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'please login to continue';
        }
    }

    private function upMulFile($data, $page = null){
        if (!empty($data['name'])) {
            try {
                $fileName =  $data['name'];
                if(empty($page)){
                    $directoryPath = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.'user';
                } else {
                    $directoryPath = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.$page;
                }
                $dir = $this->makeDirectory($directoryPath);
                if(empty($page)){
                    $directoryPath = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.'user' .DS. $this->jwtPayload->id;
                } else {
                    $directoryPath = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.$page .DS. $this->jwtPayload->id;
                }

                $dir = $this->makeDirectory($directoryPath);
                if(\move_uploaded_file($data['tmp_name'], $dir. DS . $fileName)){
                    return 1;
                }
            } catch (\Exception $e) {
                return false;
            }

        } else {
            return false;
        }
    }
    private function makeDirectory($data)
    {
        if (!file_exists($data)) {
            if (!mkdir($data, 0777, true)) {
                echo 'File Upload Failed';
            } else {
                return $data;
            }
        }
        return $data;
    }

    public function attach(){
       $this->request->allowMethod('post');
       $data = $this->request->getData();
       if(!empty($data)){
            foreach ($data['file_content'] as $file){
                if(empty($data['page'])){
                    $res = $this->upMulFile($file);
                } else {
                    $res = $this->upMulFile($file, $data['page']);
                }
                if($res == 1){
                    $this->apiResponse['message'] = 'File has been uploaded successfully.';
                }
                else {
                    $this->httpStatusCode = 400;
                    $this->apiResponse['error'] = 'File could not be uploaded. Please try again.';
                }
            }
       } else {
           $this->httpStatusCode = 403;
           $this->apiResponse['error'] = 'Please enter file content.';
       }
    }

    /* get user message attach file
     * */
    public function getAttachFile(){
        $this->request->allowMethod('get');
        $data = $this->request->getQueryParams();
        if(empty($data['page'])){
            $dir    = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.'user' .DS. $this->jwtPayload->id;
        } else {
            $dir    = ROOT . DS . 'plugins'. DS. 'Api'.DS. 'webroot'.DS.'upload'.DS.$data['page'] .DS. $this->jwtPayload->id;
        }
        $path = realpath($dir);
        if($path !== false AND is_dir($path))
        {
            $files = scandir($dir);
        } else {
            $this->apiResponse['data'] = [];
            return;
        }
        foreach ( $files as $file){
            if($file == '.' || $file == '..'){
                $files_array1[] =  ['file' => Router::url('', true)];
            } else {
                if(empty($data['page'])){
                    $url = '/api/upload/'.'user'.'/'.$this->jwtPayload->id.'/'. $file;
                } else {
                    $url = '/api/upload/'.$data['page'].'/'.$this->jwtPayload->id.'/'. $file;
                }
                $files_array[] =  ['file' => Router::url($url, true)];
            }
        }
        if(!empty($files_array)){
            $this->apiResponse['data'] = $files_array;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

}
