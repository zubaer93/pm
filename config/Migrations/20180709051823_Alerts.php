<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class Alerts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('global_alerts');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('type', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('time_alerts');
        $table->addColumn('time_of_day', 'time', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('when_happens', 'boolean', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('kind', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();
        
        $table = $this->table('email_alerts');
        $table->addColumn('global_alert_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('time_alert_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('sms_alerts');
        $table->addColumn('global_alert_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('time_alert_id', 'integer', [
            'default' => null,
            'limit' => 11,
        ]);
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        try{
            $table->create();
        }catch (Exception $e){

        }


//        $this->__createGlobalAlerts();
//        $this->generateAlertsForUsers();
    }

    /**
     * __createGlobalAlerts method it will create the global alerts
     *
     * @return void
     */
    public function __createGlobalAlerts()
    {
        $data = [
            [
                'name' => 'All Watchlist items Notifications',
                'type' => 'Watchlist'
            ],
            [
                'name' => 'All Stock Notifications',
                'type' => 'Stocks'
            ],
            [
                'name' => 'Weekly calendar events',
                'type' => 'Events'
            ]
        ];

        $GlobalAlerts = TableRegistry::get('GlobalAlerts');
        $entities = $GlobalAlerts->newEntities($data);
        $GlobalAlerts->saveMany($entities);
    }

    /**
     * generateAlertsForUsers it will create all records for the current users.
     *
     * @return void
     */
    public function generateAlertsForUsers()
    {
        $GlobalAlerts = TableRegistry::get('GlobalAlerts');
        $TimeAlerts = TableRegistry::get('TimeAlerts');
        $EmailAlerts = TableRegistry::get('EmailAlerts');
        $SmsAlerts = TableRegistry::get('SmsAlerts');
        $globalAlerts = $GlobalAlerts->find()->toArray();
        $User = TableRegistry::get('AppUsers');
        $users = $User->find('list')
            ->where(['role !=' => 'admin']);

        foreach ($users as $id => $user) {
            $emailTimeAlert = $TimeAlerts->newEntity();
            $emailTimeAlert->when_happens = 1;
            $emailTimeAlert->user_id = $id;
            $emailTimeAlert->kind = 'email';
            $TimeAlerts->save($emailTimeAlert);

            $smsTimeAlert = $TimeAlerts->newEntity();
            $smsTimeAlert->when_happens = 1;
            $smsTimeAlert->user_id = $id;
            $smsTimeAlert->kind = 'sms';
            $TimeAlerts->save($smsTimeAlert);

            $emailAlerts = [];

            $smsAlerts = [];
            foreach ($globalAlerts as $globalAlert) {
                $emailAlerts[] = [
                    'global_alert_id' => $globalAlert->id,
                    'time_alert_id' => $emailTimeAlert->id,
                    'user_id' => $id,
                ];

                $smsAlerts[] = [
                    'global_alert_id' => $globalAlert->id,
                    'time_alert_id' => $smsTimeAlert->id,
                    'user_id' => $id,
                ];
            }
            $emailEntities = $EmailAlerts->newEntities($emailAlerts);
            $smsEntities = $SmsAlerts->newEntities($smsAlerts);

            $EmailAlerts->saveMany($emailEntities);
            $SmsAlerts->saveMany($smsEntities);
        }

    }
}
