<?php


namespace Api\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use PhpParser\Error;
use RestApi\Routing\Exception\InvalidTokenException;
use RestApi\Routing\Exception\InvalidTokenFormatException;
use RestApi\Routing\Exception\MissingTokenException;
use Firebase\JWT\JWT;


class CommonComponent extends Component
{
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
        Configure::load('api', false);
    }

    public function checkRole($payLoad)
    {
        $token_data = $payLoad;
        foreach ($token_data->role as $role_list) {
            $user_role[] = $role_list;
            foreach ($user_role as $list) {
                $role[] = $list->name;
            }
        }
        return $role;
    }

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

    public function uploadFile($id)
    {
        if (!empty($_FILES['file_content']['name'])) {
            try {
                $fileName = $this->randomnum(10) . '_' . time() . '_' . str_replace(" ", "", $_FILES['file_content']['name']);
                $data_date = date("Y-m-d");
                $date = \DateTime::createFromFormat("Y-m-d", $data_date);
                $m = $date->format("m");
                $d = $date->format("d");
                $year = $date->format("Y");
                $path = 'upload' . DS . 'files' . DS . $year . DS . $m . DS . $d . DS . $id;
                $directoryPath = ROOT . 'upload' . DS . 'files' . DS . $year;
                $dir = $this->makeDirectory(ROOT . DS . $path);
                if ($dir && \move_uploaded_file($_FILES['file_content']['tmp_name'], ROOT . DS . $path . DS . $fileName)) {
                    $file_mime_type = pathinfo(ROOT . $path . DS . $fileName, PATHINFO_EXTENSION);
                    //pr($file_mime_type);exit;
                    if ($file_mime_type == 'png' || $file_mime_type == 'jpg' || $file_mime_type == 'gif' || $file_mime_type == 'jpeg') {
                        $thumb = $this->resize(200, WWW_ROOT . 'img' . DS . 'icon' . DS . $fileName, ROOT . DS . $path . DS . $fileName);
                        // pr($thumb);exit;
                        if ($thumb) {
                            \move_uploaded_file($thumb, WWW_ROOT . 'img' . DS . 'icon' . DS . $fileName);
                        }
                    }
                    return array($fileName, $path, true);
                } else {
                    return false;
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

    public function _performTokenValidation()
    {
        $request = $this->request;

        $token = '';
        $header = $request->getHeader('Authorization');
        if (!empty($header)) {
            $parts = explode(' ', $header[0]);

            if (count($parts) < 2 || empty($parts[0]) || !preg_match('/^Bearer$/i', $parts[0])) {
                throw new InvalidTokenFormatException();
            }
            $token = $parts[1];
        } elseif (!empty($this->request->getQuery('token'))) {
            $token = $this->request->getQuery('token');
        } elseif (!empty($request->getData('token'))) {
            $token = $request->getData('token');
        } else {
            throw new MissingTokenException();
        }
        try {
            $payload = JWT::decode($token, Configure::read('ApiRequest.jwtAuth.cypherKey'), [Configure::read('ApiRequest.jwtAuth.tokenAlgorithm')]);
        } catch (\Exception $e) {
            throw new InvalidTokenException();
        }

        if (empty($payload)) {
            throw new InvalidTokenException();
        }


        $this->jwtPayload = $payload;

        $this->jwtToken = $token;

        Configure::write('accessToken', $token);

        return array($this->jwtPayload, $this->jwtToken);
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

    function getDataURI($image, $mime = '')
    {
        $file = file_get_contents(WWW_ROOT . $image);
        $data = 'data:' . (function_exists('mime_content_type') ? mime_content_type(WWW_ROOT . $image) : $mime) . ';base64,' . base64_encode($file);
        return $data;
    }

    public function upMulFile($data,$id){
        if (!empty($data['name'])) {
            try {
                $fileName = $this->randomnum(10) . '_' . time() . '_' . str_replace(" ", "", $data['name']);
                $data_date = date("Y-m-d");
                $date = \DateTime::createFromFormat("Y-m-d", $data_date);
                $m = $date->format("m");
                $d = $date->format("d");
                $year = $date->format("Y");
                $path = 'upload' . DS . 'files' . DS . $year . DS . $m . DS . $d . DS . $id;
                $directoryPath = ROOT . 'upload' . DS . 'files' . DS . $year;
                $dir = $this->makeDirectory(ROOT . DS . $path);
                if ($dir && \move_uploaded_file($data['tmp_name'], ROOT . DS . $path . DS . $fileName)) {
                    $file_mime_type = pathinfo(ROOT . $path . DS . $fileName, PATHINFO_EXTENSION);
                    if ($file_mime_type == 'png' || $file_mime_type == 'jpg' || $file_mime_type == 'gif' || $file_mime_type == 'jpeg') {
                        $thumb = $this->resize(200, WWW_ROOT . 'img' . DS . 'icon' . DS . $fileName, ROOT . DS . $path . DS . $fileName);
                        // pr($thumb);exit;
                        if ($thumb) {
                            \move_uploaded_file($thumb, WWW_ROOT . 'img' . DS . 'icon' . DS . $fileName);
                        }
                    }
                    return array($fileName, $path, true);
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }

        } else {
            return false;
        }

    }
}

