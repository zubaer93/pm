<?php

namespace App\Model\Service;

use Cake\Core\Configure;
use Cake\I18n\Time;
use App\Model\Table\RatingsTable;
use Cake\ORM\TableRegistry;

class UploadFile
{

    public static function uploadFile($data)
    {
  
        $path = $data['name'];
        $filename = pathinfo($path, PATHINFO_BASENAME);
        if (!move_uploaded_file($data['tmp_name'], Configure::read('Users.financial.path') . $filename)) {
            return false;
        }
        return true;
    }

}
