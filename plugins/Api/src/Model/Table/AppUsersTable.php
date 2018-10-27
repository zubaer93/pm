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

class AppUsersTable extends UsersTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        try {
            parent::initialize($config);

            $this->addBehavior('Search.Search');

            $p = isset(Router::getRequest()->params) ? Router::getRequest()->params : null;
            if (!(isset($p['prefix']) && $p['prefix'] === 'admin')) {
                $this->addBehavior('User', [
                    'events' => ['Model.beforeFind'],
                    'field' => 'enable',
                ]);
            }

            $this->addBehavior('CakeDC/Enum.Enum', [
                'lists' => [
                    'account_type' => [
                        'strategy' => 'const',
                        'prefix' => 'ACCOUNT_TYPE',
                        'applicationRules' => false
                    ]
                ]
            ]);

            $this->hasMany('IpoInterestedUsers', [
                'foreignKey' => 'app_user_id',
                'joinType' => 'INNER'
            ]);

            $this->hasMany('Notifications', [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER'
            ]);

            $this->hasMany('TimeAlerts');
            $this->hasMany('EmailAlerts');
            $this->hasMany('SmsAlerts');
            $this->hasMany('Watchlist');
            $this->hasMany('Messages');
            $this->hasMany('WatchlistBonds');
            $this->hasMany('WatchlistForex');
            $this->hasMany('WatchlistGroup');
        } catch (\Exception $e) {

        }
    }

  
    

    public function getUserAccountType($userId)
    {
        $currentUser = $this->find()
            ->where(['AppUsers.id' => $userId])
            ->first();
  
        $interval = date_diff($currentUser['created'], date_create());

        if (!$currentUser) {
            $currentUser = (object)['account_type' => 'FREE'];
        }
        return $currentUser;
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        // $validator
        //     ->requirePresence('username', 'create')
        //     ->notEmpty('username');
        $validator->add('email', [
            'validEmail' => [
                'rule' => 'email',
                'message' => 'Invalid format',
            ],
            'email' => [
                'rule' => function ($email) {
                    if (!preg_match('/^[^0-9][_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
                        return false;
                    }
                    return true;
                },
                'message' => 'Invalid format',
            ]
        ]);
        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->notEmpty('first_name');

        $validator
            ->notEmpty('last_name');

        $validator
            ->allowEmpty('token');

        $validator
            ->add('token_expires', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('token_expires');

        $validator->add('date_of_birth', [
            'validDate' => [
                'rule' => 'date',
                'message' => 'Invalid format',
            ],
            'is18plus' => [
                'rule' => function ($context) {
                    if (time() < strtotime('+18 years', strtotime($context))) {
                        return false;
                    }
                    return true;
                },
                'message' => 'Your age is under 18...',
            ]
        ]);

        $validator
            ->allowEmpty('api_token');

        $validator
            ->add('activation_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('activation_date');

        $validator
            ->add('tos_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('tos_date');

        $validator
            ->allowEmpty('avatar')
            ->add('avatar', [
                'uploadError' => [
                    'rule' => 'uploadError',
                    'message' => __('The logo upload failed.'),
                    'on' => function ($context) {
                        if (is_string($context['data']['avatar'])) {
                            return strpos($context['data']['avatar'], 'http') < 0;
                        }
                    }
                ],
                'mimeType' => [
                    'rule' => ['mimeType', ['image/png', 'image/jpg', 'image/jpeg']],
                    'message' => __('Please upload images only (png, jpg).'),
                    'on' => function ($context) {
                        if (is_string($context['data']['avatar'])) {
                            return strpos($context['data']['avatar'], 'http') < 0;
                        }
                    }
                ],
                'fileSize' => [
                    'rule' => ['fileSize', '<=', '2MB'],
                    'message' => __('Logo image must be less than 2MB.'),
                    'on' => function ($context) {
                        if (is_string($context['data']['avatar'])) {
                            return strpos($context['data']['avatar'], 'http') < 0;
                        }
                    }
                ],
            ]);

        return $validator;
    }

    public function search($query)
    {
        if (!isset($query['phrase']) && isset($query['q'])) {
            $query['phrase'] = $query['q'];
        }

        if (preg_match('/$/', $query['phrase'])) {
            $query['phrase'] = str_replace('$', '', $query['phrase']);
        }

        if (preg_match('/@/', $query['phrase'])) {
            $query['phrase'] = str_replace('@', '', $query['phrase']);
        }

        $query = $this->find('search', ['search' => $query])
            ->limit(8)
            ->all();
        $items = [];
        foreach ($query as $key => $item) {
            if (file_exists(WWW_ROOT . $item['avatarPath'])) {
                $avatar = $item->avatarPath;
            } else {
                $avatar = $item['avatar'];
            }
            $items[$key] = [
                'type' => 'user',
                'fullname' => $item['first_name'] . ' ' . $item['last_name'],
                'username' => $item['username'],
                'icon' => $avatar,
            ];
        }
        return $items;
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager()
    {
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
            ->add('phrase', 'Search.Like', [
                'before' => true,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => ['username']
            ]);
        return $searchManager;
    }

    /**
     * Verify if username login is email or username
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findAuth(Query $query, array $options = [])
    {
        $query
            ->orWhere(['email' => $options['username']])
            ->orWhere(['username' => $options['username']])
            ->find('active', $options);

        return $query;
    }

    public function getUserInfo($username)
    {
        $user = $this->find()->where(['AppUsers.username' => $username])->first();
        return $user;
    }

    public function getUserInfo2($id)
    {
        $user = $this->find()->where(['AppUsers.id' => $id])->first();
        return $user;
    }

    public function getUserInfoWithId($id)
    {
        $user = $this->find()->where(['AppUsers.id' => $id])->first();
        return $user;
    }

    public function getMentionUsers()
    {
        $users = $this->find('all', [
            'fields' => [
                'first_name',
                'last_name',
                'username',
                'avatar'
            ]
        ]);
        return $users;
    }

    /**
     * uploadFile method
     *
     * @param $data array data from request
     * @return bool
     */
    public function uploadFile($data)
    {
        $path = $data['avatar']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $filename = $data['id'] . '.' . $ext;
        $directoryPath = WWW_ROOT . DS . 'upload';
        $directory = ROOT . DS . 'webroot'.DS.'upload'.DS.'avatar' . DS;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!move_uploaded_file($data['avatar']['tmp_name'], $directory . $filename)) {
            return false;
        }
        $user = $this->get($data['id']);
        $user->avatar = '\webroot\upload\avatar' . DS . $filename;
        return $this->save($user);
    }

    public function uploadDoc($data)
    {   
       
        

        if(!empty($data['nid'])){
            $path1 = $data['nid']['name'];
            $ext1 = pathinfo($path1, PATHINFO_EXTENSION);
            $filenameNid = $data['id'] . '.' . $ext1;

            $directoryNid = ROOT . DS .'webroot'.DS.'upload'.DS.'nid'.DS;

            if (!file_exists($directoryNid)) {
                mkdir($directoryNid, 0777, true);
            }
            if (!move_uploaded_file($data['nid']['tmp_name'], $directoryNid . $filenameNid)) {
                return false;
            }
        }
        if(!empty($data['verifyPhoto'])){
            $path2 = $data['verifyPhoto']['name'];
            $ext2 = pathinfo($path2, PATHINFO_EXTENSION);
            $filenamePhoto = $data['id'] . '.' . $ext2;

            $directoryPhoto = ROOT . DS .'webroot'.DS.'upload'.DS.'verifyPhoto'.DS;

            if (!file_exists($directoryPhoto)) {
                mkdir($directoryPhoto, 0777, true);
            }
            if (!move_uploaded_file($data['verifyPhoto']['tmp_name'], $directoryPhoto . $filenamePhoto)) {
                return false;
            }
        }
        if(!empty($data['utility_bill'])){
            $path3 = $data['utility_bill']['name'];
            $ext3 = pathinfo($path3, PATHINFO_EXTENSION);
            $filenameUtilityBill = $data['id'] . '.' . $ext3;

            $directoryUtilityBill = ROOT . DS .'webroot'.DS.'upload'.DS.'utility_bill'.DS;

            if (!file_exists($directoryUtilityBill)) {
                mkdir($directoryUtilityBill, 0777, true);
            }
            if (!move_uploaded_file($data['utility_bill']['tmp_name'], $directoryUtilityBill . $filenameUtilityBill)) {
                return false;
            }
        }
        if(!empty($data['statement'])){
            $path4 = $data['statement']['name'];
            $ext4 = pathinfo($path4, PATHINFO_EXTENSION);
            $filenameStatement = $data['id'] . '.' . $ext4;
            $directoryStatement = ROOT . DS .'webroot'.DS.'upload'.DS.'statement'.DS;
          
            if (!file_exists($directoryStatement)) {
                mkdir($directoryStatement, 0777, true);
            }
            
            if ((!move_uploaded_file($data['statement']['tmp_name'], $directoryStatement . $filenameStatement))) {
                return false;
            }
        }
        if(!empty($data['others'])){
            $path5 = $data['others']['name'];
            $ext5 = pathinfo($path5, PATHINFO_EXTENSION);
            $filenameOthers = $data['id'] . '.' . $ext5;

            $directoryOthers = ROOT . DS .'webroot'.DS.'upload'.DS.'others'.DS;

            if (!file_exists($directoryOthers)) {
                mkdir($directoryOthers, 0777, true);
            }
            if (!move_uploaded_file($data['others']['tmp_name'], $directoryOthers . $filenameOthers)) {
                return false;
            }
        }

        $user = $this->get($data['id']);
        
        if(!empty($data['others'])){
            $user->others_doc = '\webroot\upload\others' . DS . $filenameOthers;
        }
        if(!empty($data['nid'])){
            $user->nid = '\webroot\upload\nid' . DS . $filenameNid;
        }
        if(!empty($data['verifyPhoto'])){
            $user->verifyPhoto = '\webroot\upload\verifyPhoto' . DS . $filenamePhoto;
        }
        if(!empty($data['utility_bill'])){
            $user->utility_bill = '\webroot\upload\utility_bill' . DS . $filenameUtilityBill;
        }
        if(!empty($data['statement'])){
            $user->statement1 = '\webroot\upload\statement' . DS . $filenameStatement;
        }
        return $this->save($user);
     
    }

    /**
     * __folderInstance method this method will return an instance of Folder class
     *
     * @return Folder Instance
     */
    private function __folderInstance()
    {
        return new Folder();
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function disableUser($id)
    {
        $result = false;
        if (!is_null($id)) {
            $user = $this->get($id);
            $user->enable = 1;
            if ($this->save($user)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function enableUser($id)
    {
        $result = false;
        if (!is_null($id)) {
            $user = $this->get($id);
            $user->enable = 0;
            if ($this->save($user)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change password method
     *
     * @param EntityInterface $user user data.
     * @throws WrongPasswordException
     * @return mixed
     */
    public function changePassword(EntityInterface $user)
    {
        try {
            $currentUser = $this->get($user->id, [
                'contain' => []
            ]);
        } catch (RecordNotFoundException $e) {
            throw new UserNotFoundException(__d('CakeDC/Users', "User not found"));
        }

        $user = $this->save($user);
        if (!empty($user)) {
            $user->token = null;
            $user->token_expires = null;
            $user = $this->save($user);
        }
        return $user;
    }



}
