<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * Messages Model
 *
 * @property \App\Model\Table\AppUsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Message get($primaryKey, $options = [])
 * @method \App\Model\Entity\Message newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Message[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Message|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Message patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Message[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Message findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MessagesTable extends Table
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

        $this->setTable('messages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Ratings', [
            'foreignKey' => 'message_id'
        ]);

        $this->hasMany('ScreenshotMessage', [
            'foreignKey' => 'message_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('message', 'create')
                ->notEmpty('message');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'AppUsers'));

        return $rules;
    }

    public function getFeedData($data, $userInfo)
    {
        $rating = TableRegistry::get('Ratings');

        $feedData = [
            'Message' => [
                'message' => $data['message'],
                'attachment' => SITE_URL.Configure::read('Messages.attach.path').$data['attachment'],
                'rating' => $rating::getAverageRanking($data['id']),
                'message_id' => $data['id'],
                'message_date' => $data['created']->nice(),
                'message_comment_id' => $data['comment_id']
            ],
            'User' => [
                'user_id' => $userInfo['id'],
                'username' => $userInfo['username'],
                'fullname' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                'experience' => (!is_null($userInfo['experince_id'])) ? \App\Model\Service\Core::$experience[$userInfo['experince_id']] : '',
                'investment_style' => (!is_null($userInfo['investment_style_id'])) ? \App\Model\Service\Core::$investmentStyle[$userInfo['investment_style_id']] : ''
            ]
        ];
        return $feedData;
    }

    public function getCommentsData($data, $userInfo)
    {
        $rating = TableRegistry::get('Ratings');

        $commentData = [
            'Message' => [
                'message' => $data['message'],
                'rating' => $rating::getAverageRanking($data['id']),
                'message_id' => $data['id'],
                'message_date' => $data['created']->nice(),
                'message_comment_id' => $data['comment_id']
            ],
            'User' => [
                'user_id' => $userInfo['id'],
                'username' => $userInfo['username'],
                'fullname' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                'experience' => (!is_null($userInfo['experince_id'])) ? \App\Model\Service\Core::$experience[$userInfo['experince_id']] : '',
                'investment_style' => (!is_null($userInfo['investment_style_id'])) ? \App\Model\Service\Core::$investmentStyle[$userInfo['investment_style_id']] : ''
            ]
        ];
        return $commentData;
    }

    /**
     * getModalData()
     *
     * @param $data,$userInfo
     * @return array
     */
    public function getModalData($data, $userInfo)
    {
        $rating = TableRegistry::get('Ratings');

        $ModalData = [
            'Message' => [
                'message' => $data['message'],
                'rating' => $rating::getAverageRanking($data['id']),
                'message_id' => $data['id'],
                'message_date' => $data['created']->nice()
            ],
            'User' => [
                'user_id' => $userInfo['id'],
                'username' => $userInfo['username'],
                'fullname' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                'experience' => (!is_null($userInfo['experince_id'])) ? \App\Model\Service\Core::$experience[$userInfo['experince_id']] : '',
                'investment_style' => (!is_null($userInfo['investment_style_id'])) ? \App\Model\Service\Core::$investmentStyle[$userInfo['investment_style_id']] : ''
            ]
        ];
        return $ModalData;
    }

    public function getMessage($id)
    {
        try {
            return $this->get($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * getParentData() method will return message parent message
     *
     * @param $id message id
     * @return array
     */
    public static function getParentData($id)
    {
        $Messages = TableRegistry::get('Messages');
        $data = $Messages->find()
                ->where(['Messages.id' => $id])
                ->contain(['AppUsers'])
                ->contain(['Ratings'])
                ->contain(['ScreenshotMessage'])
                ->first();
        return $data;
    }

    /**
     * getPostComment() method will return message comments
     *
     * @param $id message id
     * @return array
     */
    public static function getPostComment($id)
    {
        $Messages = TableRegistry::get('Messages');

        $data = $Messages->find()
                ->where(['Messages.comment_id' => $id])
                ->contain(['AppUsers'])
                ->contain(['Ratings'])
                ->contain(['ScreenshotMessage'])
                ->order(['Messages.created' => 'ASC'])
                ->toArray();
        return $data;
    }

    /**
     * getPostCommentsCount() method will return message comments count
     *
     * @param $id message id
     * @return array
     */
    public static function getPostCommentsCount($id)
    {
        $Messages = TableRegistry::get('Messages');
        $data = $Messages->find()
                ->where(['Messages.comment_id' => $id])
                ->contain(['AppUsers'])
                ->contain(['Ratings'])
                ->contain(['ScreenshotMessage'])
                ->count();

        return $data;
    }

    private function getCurrentDateTime()
    {
        return $now = date("M\ j \a\\t g:i A");
    }

    private function getAvatarPath($avatar)
    {
        $avatar = null;
        if (!empty($avatar)) {
            return $avatar;
        } else {
            $avatar = Configure::read('Users.Avatar.placeholder');
        }

        return $avatar;
    }

    /**
     * editMessagesWithAdmin method will edit many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function editMessageWithAdmin($data)
    {
        $result = true;
        if (!is_null($data)) {
            $message = $this->get($data['id']);
            $message->country_id = (int) $data['market'];
            $message->message = $data['message'];
            $message->company_id = $data['company_id'];
            if (!$this->save($message)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * deletNewsWithAdmin method will delete news
     *
     * @param array $data articles data
     * @return bool
     */
    public function deletMessageWithAdmin($id)
    {
        $result = true;
        if (!is_null($id)) {
            $ScreenshotMessage = TableRegistry::get('ScreenshotMessage');
            $Screenshot = $ScreenshotMessage->find()
                            ->where(['message_id' => $id])->first();
            $comments = $this->find()->where(['comment_id' => $id]);
            foreach ($comments as $val) {
                $this->delete($val);
            }
            if (!is_null($Screenshot)) {
                $ScreenshotMessage->delete($Screenshot);
            }

            $entity = $this->get($id);
            if (!$this->delete($entity)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * setPostWithAdmin method will save many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function setMessageWithAdmin($data)
    {
        $result = true;
        if (!empty($data)) {
            $message = $this->newEntity();
            $message->message = $data['message'];
            $message->country_id = $data['market'];
            $message->company_id = $data['company_id'];
            $message->user_id = $data['user_id'];
            if (!$this->save($message)) {
                $result = false;
            }
        }

        return $result;
    }
    public function RandomString($qtd){ 
    //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
    $Caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789'; 
    $QuantidadeCaracteres = strlen($Caracteres); 
    $QuantidadeCaracteres--; 

    $Hash=NULL; 
        for($x=1;$x<=$qtd;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        } 

    return $Hash; 
    } 
   public function uploadFile($data)
    {       
        $path = $data['file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $name = $this->RandomString(20);
        $filename = $name . '.' . $ext;
        if (!move_uploaded_file($data['file']['tmp_name'], Configure::read('Messages.attach.fullpath') . $filename)) {
           // return false;
        }
        return $filename;

    }
}
