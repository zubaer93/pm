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

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');
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
        $directory = ROOT . DS . 'webroot\upload\avatar' . DS;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!move_uploaded_file($data['avatar']['tmp_name'], $directory . $filename)) {
            return false;
        }
        $user = $this->get($data['id']);
        $user->avatar = 'webroot\upload\avatar' . DS . $filename;
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
     * saveAlerts method this method will save and modify de alerts based in the logged user.
     *
     * @param array $data data from request
     * @param string $userId Logged User
     * @return bool
     */
    public function saveAlerts($data, $userId)
    {
        $emailConfig['global_alert_id']['1'] = $data['email_watchlist'];
        $emailConfig['global_alert_id']['2'] = $data['email_stock'];
        $emailConfig['global_alert_id']['3'] = $data['email_event'];
        $emailConfig['time_alerts']['time_of_day']['hour'] = $data['email_hour'];
        $emailConfig['time_alerts']['time_of_day']['minute'] = $data['email_minute'];
        $emailConfig['time_alerts']['when_happens'] = $data['email_when_happens'];

        $smsConfig['global_alert_id']['1'] = $data['sms_watchlist'];
        $smsConfig['global_alert_id']['2'] = $data['sms_stock'];
        $smsConfig['global_alert_id']['3'] = $data['sms_event'];
        $smsConfig['time_alerts']['time_of_day']['hour'] = $data['sms_hour'];
        $smsConfig['time_alerts']['time_of_day']['minute'] = $data['sms_minute'];
        $smsConfig['time_alerts']['when_happens'] = $data['sms_when_happens'];

        $this->getConnection()->begin();
        $this->EmailAlerts->deleteAll(['user_id' => $userId]);
        $this->SmsAlerts->deleteAll(['user_id' => $userId]);
        $this->TimeAlerts->deleteAll(['user_id' => $userId]);

        $emailTimeAlert = $this->__processTimeAlert($emailConfig, 'email', $userId);

        $emailEntity = $this->TimeAlerts->newEntity($emailTimeAlert);
        if (!empty($emailEntity) && !$this->TimeAlerts->save($emailEntity)) {
            $this->getConnection()->rollback();
            return false;
        }

        $smsTimeAlert = $this->__processTimeAlert($smsConfig, 'sms', $userId);

        $smsEntity = $this->TimeAlerts->newEntity($smsTimeAlert);
        if (!empty($smsEntity) && !$this->TimeAlerts->save($smsEntity)) {
            $this->getConnection()->rollback();
            return false;
        }

        $newEmailAlert = $this->__processAlertData($emailConfig, $emailEntity->id, $userId);

        $entities = $this->EmailAlerts->newEntities($newEmailAlert);

        if (!empty($entities) && !$this->EmailAlerts->saveMany($entities)) {
            $this->getConnection()->rollback();
            return false;
        }

        $smsEmailAlert = $this->__processAlertData($smsConfig, $smsEntity->id, $userId);
        $entities = $this->SmsAlerts->newEntities($smsEmailAlert);
        if (!empty($entities) && !$this->SmsAlerts->saveMany($entities)) {
            $this->getConnection()->rollback();
            return false;
        }

        $this->getConnection()->commit();

        return true;
    }

    /**
     * saveAlerts method this method will save and modify de alerts based in the logged user.
     *
     * @param array $data data from request
     * @param string $userId Logged User
     * @return bool
     */
    public function saveInitialAlerts($data, $userId, $is_initial = false)
    {
        $emailConfig = Hash::get($data, 'email_alerts');
        $smsConfig = Hash::get($data, 'sms_alerts');
        $this->getConnection()->begin();

        if ($this->EmailAlerts->deleteAll(['user_id' => $userId]) === false) {
            $this->getConnection()->rollback();
            return false;
        }


        if ($this->SmsAlerts->deleteAll(['user_id' => $userId]) === false) {
            $this->getConnection()->rollback();
            return false;
        }


        if ($this->TimeAlerts->deleteAll(['user_id' => $userId]) === false) {
            $this->getConnection()->rollback();
            return false;
        }

        $emailTimeAlert = $this->__processTimeAlert($emailConfig, 'email', $userId);
        $emailEntity = $this->TimeAlerts->newEntity($emailTimeAlert);
        if (!$this->TimeAlerts->save($emailEntity)) {
            $this->getConnection()->rollback();
            return false;
        }

        $smsTimeAlert = $this->__processTimeAlert($smsConfig, 'sms', $userId);
        $smsEntity = $this->TimeAlerts->newEntity($smsTimeAlert);
        if (!$this->TimeAlerts->save($smsEntity)) {
            $this->getConnection()->rollback();
            return false;
        }

        $newEmailAlert = $this->__processAlertData($emailConfig, $emailEntity->id, $userId);
        $entities = $this->EmailAlerts->newEntities($newEmailAlert);
        if (!empty($entities)) {
            if (!$this->EmailAlerts->saveMany($entities)) {
                $this->getConnection()->rollback();
                return false;
            }
        }

        $smsEmailAlert = $this->__processAlertData($smsConfig, $smsEntity->id, $userId);
        $entities = $this->SmsAlerts->newEntities($smsEmailAlert);

        if (!empty($entities)) {
            if (!$this->SmsAlerts->saveMany($entities)) {
                $this->getConnection()->rollback();
                return false;
            }
        }

        $this->getConnection()->commit();

        return true;
    }

    /**
     * __processEmailTimeAlert method it will prepare the email data to be saved
     *
     * @param array $data Email Data to be saved
     * @param $kind
     * @param $userId
     * @return array
     */
    private function __processTimeAlert($data, $kind, $userId)
    {
        $timeOfDay = false;

        if (!empty($data['time_alerts']['time_of_day']['hour']) || !empty($data['time_alerts']['time_of_day']['minute'])) {
            $timeOfDay = $data['time_alerts']['time_of_day']['hour'] . ':' . $data['time_alerts']['time_of_day']['minute'];
        }

        return [
            'user_id' => $userId,
            'kind' => $kind,
            'when_happens' => (bool)$data['time_alerts']['when_happens'],
            'time_of_day' => $timeOfDay
        ];
    }

    /**
     * __processEmailAlertData method it will prepare the email data to be saved
     *
     * @param array $data Email Data to be saved
     * @param $timeId
     * @param $userId
     * @return array
     */
    private function __processAlertData($data, $timeId, $userId)
    {
        $newAlerts = [];
        foreach ($data['global_alert_id'] as $id => $value) {
            if (!empty($value)) {
                $newAlerts[] = [
                    'global_alert_id' => $id,
                    'time_alert_id' => $timeId,
                    'user_id' => $userId,
                ];
            }
        }

        return $newAlerts;
    }

    /**
     * __defaultAlerts method it will save default settings when a new users sign up.
     *
     * @return array
     */
    private function __defaultAlerts()
    {
        return [
            'email_alerts' => [
                'global_alert_id' => [
                    1 => '1',
                    2 => '0',
                    3 => '1'
                ],
                'time_alerts' => [
                    'when_happens' => '1'
                ]
            ],
            'sms_alerts' => [
                'global_alert_id' => [
                    1 => '0',
                    2 => '0',
                    3 => '0'
                ],
                'time_alerts' => [
                    'when_happens' => '0'
                ]
            ]
        ];
    }

    /**
     * notify method it will send the notifications based in their global notifications settings
     *
     * @param array $companyIds Company Ids.
     * @param string $type Type of alert to be instantiated.
     * @param array $currentRecord Current record saved to use the info in the email.
     * @return void
     */
    public function notify($companyIds, $type, $currentRecord)
    {
        $emailAlertKind = 'email';
        $users = $this->Watchlist->getStocksAlerts($companyIds, $emailAlertKind);

        if (!$users->isEmpty()) {
            $emailAlert = $this->__loadAlertClass($emailAlertKind, $type);
            $emailAlert->notify($users, $currentRecord);
        }

        $smsAlertKind = 'sms';
        if (!$users->isEmpty()) {
            $smsAlert = $this->__loadAlertClass($smsAlertKind, $type);
            $smsAlert->notify($users, $currentRecord);
        }
    }

    /**
     * __loadAlertClass method it will return a EmailAlert or SmsAlert instance class.
     *
     * @param string $kind What class should be instantiated.
     * @param $type
     * @return EmailAlert SmsAlert
     */
    private function __loadAlertClass($kind, $type)
    {
        $className = $type . ucfirst($kind) . 'Alert';
        $class = '\App\Notifications\\' . ucfirst($kind) . '\\' . $className;

        return new $class();
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
