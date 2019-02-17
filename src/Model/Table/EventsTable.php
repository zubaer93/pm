<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Events Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 */
class EventsTable extends Table
{

    private $enable = 0;
    private $disable = 1;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('title');

        $validator
            ->allowEmpty('activity_type');

        $validator
            ->allowEmpty('description');

        $validator
            ->time('time')
            ->allowEmpty('time');

        $validator
            ->date('date')
            ->allowEmpty('date');

        $validator
            ->allowEmpty('location');

        $validator
            ->allowEmpty('meeting_link');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    public function add($data)
    {
        $result = false;
        if (!is_null($data)) {

            $event = $this->newEntity();

            $event->company_id = $data['company_id'];
            $event->title = $data['title'];
            $event->activity_type = $data['activity_type'];
            $event->description = $data['description'];
            $event->time = $data['time'];
            $event->location = $data['location'];
            $event->date = $data['date'];
            $event->meeting_link = $data['meeting_link'];

            $result = $this->save($event);
        }
        return $result;
    }

    public function edit($data, $id)
    {
        $result = false;
        if (!is_null($data) && !is_null($id)) {

            $event = $this->get($id);

            $event->company_id = $data['company_id'];
            $event->title = $data['title'];
            $event->activity_type = $data['activity_type'];
            $event->description = $data['description'];
            $event->time = $data['time'];
            $event->location = $data['location'];
            $event->date = $data['date'];
            $event->meeting_link = $data['meeting_link'];

            $result = $this->save($event);
        }
        return $result;
    }

    public function getCompany($language)
    {
        $company = TableRegistry::get('Companies');
        $query = $company->find('list')
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                        }]);
                }])
            ->where(['enable !=' => 1]);
        return $query;
    }

    public function getEvent($id)
    {
        $data = $this->find()
            ->where(['company_id' => $id])
            ->toArray();
        return $data;
    }

    public function getCalendar($date, $currentLanguage)
    {
        if ($date === 'week') {
            $info = '+7 days';
        } elseif ($date === 'month') {
            $info = '+1 month';
        } elseif ($date === 'year') {
            $info = '+1 year';
        }
        $info_date = (new Time('America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");
        $info_end = (new Time('America/New_York'))->setTimezone('US/Eastern')->modify($info)->format("Y-m-d H:i:s");
  
        $data = $this->find()
            ->where(['Events.date >=' => $info_date])
            ->where(['Events.date <=' => $info_end])
            ->order(['Events.date' => 'ASC'])
            ->contain(['Companies' => function ($q) use ($currentLanguage) {
                return $q->contain(['Exchanges' => function ($q) use ($currentLanguage) {
                    return $q->contain(['Countries' => function ($q) use ($currentLanguage) {
                            return $q->where(['Countries.market' => $currentLanguage]);
                        }]);
                    }]);
            }]);

        return $data;
    }

    public function disable($id)
    {
        $result = false;
        if (!is_null($id)) {
            $event = $this->get($id);
            $event->enable = $this->disable;
            if ($this->save($event)) {
                $result = true;
            }
        }
        return $result;
    }

    public function enable($id)
    {
        $result = false;
        if (!is_null($id)) {
            $event = $this->get($id);
            $event->enable = $this->enable;
            if ($this->save($event)) {
                $result = true;
            }
        }
        return $result;
    }

    public function deleteEvent($id)
    {
        $result = false;
        if (!is_null($id)) {
            $event = $this->get($id);
            if ($this->delete($event)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     *
     * @param type $name
     * @return boolean
     */
    public function checkEvent($name)
    {
        $result = true;
        if (!is_null($name)) {
            $data = $this->find()
                ->where(['title' => $name])
                ->first();
            if (is_null($data)) {
                return false;
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function addEventScrapper($data)
    {
        $event = $this->newEntity();

        $event->company_id = $data['company_id'];
        $event->title = $data['name'];
        $event->activity_type = $data['name'];
        $event->description = $data['description'];
        $event->time = $data['time'];
        $event->location = $data['address'];
        $event->date = $data['date'];
        $event->meeting_link = '';

        $result = $this->save($event);

        if ($result) {
            $this->notify($event);
        }

        return $result;
    }

    /**
     * notify method it will send the notifications based in their global notifications settings
     *
     * @param array $currentRecord Current record saved to use the info in the email.
     * @return void
     */
    public function notify($currentRecord)
    {
        $AppUsers = TableRegistry::get('AppUsers');
        $AppUsers->notify($currentRecord->company_id, 'Events', $currentRecord);
    }
}
