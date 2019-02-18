<?php

namespace Api\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Api\Routing\Exception\InvalidTokenException;

class AppController extends RestApiController
{

    const SUBDOMAIN_STOCKGITTER = 'stockgitter';
    const STAGE_SUBDOMAIN_STOCKGITTER = 'stage';
    public $currentLanguage;

    public function initialize()
    {
        parent::initialize();
        $this->_setCurrentLanguage();
    }

    /*this function will return current language from url such as USD/JMD
     * by default it will return USD
     * */
    protected function _setCurrentLanguage()
    {
        $this->currentLanguage = $this->request->getParam('lang') ;
        if (!$this->currentLanguage) {
            $this->currentLanguage = 'USD';
        }
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
       /* if (Configure::read('ApiRequest.jwtAuth.enabled')) {
            $this->manageToken($event);
        }*/
       return true;
    }

    private function manageToken(Event $event)
    {

        $request = $event->getSubject()->request;

        if (!empty($request->params['allowWithoutToken']) && $request->params['allowWithoutToken']) {
            return true;
        }
        /*if ($this->jwtPayload && $this->jwtPayload->id && $request['action'] != 'logout' && $request['action'] != 'refreshToken') {
            $this->add_model(array('Users'));
            $user = $this->Users->find()->where(['api_token' => $this->jwtToken])->first();
            if ($user && date('Y-m-d', strtotime($user['token_expires'])) <= date('Y-m-d')) {
                throw new InvalidTokenException();
            }
            if ($user) {
                return true;
            } else {
                throw new InvalidTokenException();
            }

        }*/
        else{
            throw new InvalidTokenException();
        }
    }

    public function add_model($models)
    {
        if (is_array($models)) {
            foreach ($models as $model) {
                $this->loadModel($model);
            }
        } else {
            $this->loadModel($models);
        }
    }

    /**
     * Random Alpha-Numeric String
     *
     * @param int length
     * @return string
     * @access public
     */
    function randomnum($length)
    {
        $randstr = "";
        srand((double)microtime() * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        for ($rand = 0; $rand <= $length; $rand++) {
            $random = rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }
    protected function _getCurrentSubDomain()
    {
        $url = $this->_getUrl();
        $url = parse_url($url, PHP_URL_HOST);
        $url = strstr(str_replace("www.", "", $url), ".", true);
        $subdomain = ($url != self::SUBDOMAIN_STOCKGITTER) ? $url : null;
      
        $this->set(compact('subdomain'));
        return $subdomain;
    }
    protected function _getUrl()
    {
        $url = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
    }
    /* Generate 6 digit ramdom number using mt_rand() function
     * */
    public function six_digit_random_number()
    {
        return mt_rand(100000, 999999);
    }

    public function send_email($options = array())
    {
        $template = $options['template'];
        $email = new \Cake\Mailer\Email();

        if (!empty($options['activation'])) {
            $email->viewVars(array('activation' => $options['activation']));
        }
        if (!empty($options['link'])) {
            $email->viewVars(array('link' => $options['link']));
        }
        if (!empty($options['user_details'])) {
            $email->viewVars(array('user_details' => $options['user_details']));
        }
        if (!empty($options['message'])) {
            $email->viewVars(array('message' => $options['message']));
        }
        if (!empty($options['email'])) {
            $email->viewVars(array('email' => $options['email']));
        }
        if (!empty($options['password'])) {
            $email->viewVars(array('password' => $options['password']));
        }
        if (!empty($options['job_name'])) {
            $email->viewVars(array('job_name' => $options['job_name']));
        }
        if (!empty($options['subject'])) {
            $email->viewVars(array('subject' => $options['subject']));
        }
        if (!empty($options['first_name'])) {
            $email->viewVars(array('first_name' => $options['first_name']));
        }
        if (!empty($options['last_name'])) {
            $email->viewVars(array('last_name' => $options['last_name']));
        }
        if (!empty($options['mobile'])) {
            $email->viewVars(array('mobile' => $options['mobile']));
        }
        // original name
        if (!empty($options['cv'])) {
            $email->viewVars(array('cv' => $options['cv']));
        }
        // encrypted cv name
        if (!empty($options['cv_name'])) {
            $email->viewVars(array('cv_name' => $options['cv_name']));
        }
        if (!empty($options['additional_information'])) {
            $email->viewVars(array('additional_information' => $options['additional_information']));
        }
        try {
            if (!empty($options['cv'])) {
                $email->template($template, 'email_layout')
                    ->emailFormat('html')
                    ->to($options['to'])
                    ->from('support@pocketmoney.com')
                    ->subject($options['subject'])
                    ->attachments([$options['cv'] => WWW_ROOT . 'upload' . DS . 'cv' . DS . $options['cv_name']])
                    ->send();
            } else {
                $email->template($template, 'email_layout')
                    ->emailFormat('html')
                    ->to($options['to'])
                    ->from('support@pocketmoney.com')
                    ->subject($options['subject'])
                    ->send();
            }

            return true;
        } catch (\SocketException $exception) {
            return false;
        }
    }


    function resize($newWidth, $targetFile, $originalFile)
    {

        $info = getimagesize($originalFile);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                return false;
        }

        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);

        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (file_exists($targetFile)) {
            unlink($targetFile);
        }
        $image_save_func($tmp, "$targetFile");
        return true;
    }

    public function fileUpload($type)
    {
        if (!empty($_FILES['file']['name']) || !empty($_FILES['cv']['name'])) {

            $imageName = $this->randomnum(10) . '_' . time() . '_' . str_replace(" ", "", $_FILES['file']['name']);
            $directoryPath = WWW_ROOT . 'upload';
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath);
            }
            $directoryPath = WWW_ROOT . 'upload' . DS . $type;
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath);
            }
            move_uploaded_file($_FILES['file']['tmp_name'], $directoryPath . DS . $imageName);
            $file_mime_type = pathinfo(WWW_ROOT . DS . $directoryPath . DS . $imageName, PATHINFO_EXTENSION);
            //pr($file_mime_type);exit;
            if ($file_mime_type == 'png' || $file_mime_type == 'jpg' || $file_mime_type == 'gif' || $file_mime_type == 'jpeg') {
                $thumb = $this->resize(200, $directoryPath . DS . 'thumb_' . $imageName, $directoryPath . DS . $imageName);

                if ($thumb) {
                    \move_uploaded_file($thumb, $directoryPath . DS . 'thumb_' . $imageName);
                }
            }
            return $imageName;
        } else {
            return false;
        }
    }

}
