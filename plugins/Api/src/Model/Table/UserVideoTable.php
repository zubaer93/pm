<?php

namespace Api\Model\Table;
use Cake\Event\Event;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\Utility\Hash;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\Validation\Validator;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserVideoTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('user_video');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

       /**
     * uploadVideo method
     *
     * @param $data array data from request
     * @return bool
     */
    public function uploadVideo($data)
    {  
        if($data['type'] == 'youtube'){
            $save_video = $this->newEntity();
            $save_video->user_id =  $data['id'];
            $save_video->video_type =  $data['type'];
            $save_video->video_link = $data['video'];
            return $this->save($save_video);
        }
        else{
            $filename = $data['video']['name'];
            $directory = ROOT . DS . 'plugins\Api\webroot\upload\video'.DS.$data['id'] . DS;
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            $video_title = str_replace(' ', '', $filename);
            if (!move_uploaded_file($data['video']['tmp_name'], $directory . $filename)) {
                return false;
            }
            $checkDuplicate = $this->find()->where(['video_title' => $filename, 'user_id' => $data['id']])->first();
            if(!$checkDuplicate){
                $save_video = $this->newEntity();
                $save_video->user_id =  $data['id'];
                $save_video->video_title =  $filename;
                $save_video->video_type =  $data['type'];
                $save_video->video_link = 'plugins\Api\webroot\upload\video'.DS.$data['id'] . DS.$video_title;
                return $this->save($save_video);
            }
            else{
                return false;
            }
        }   
    }
}
