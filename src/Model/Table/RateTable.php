<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Countries Model
 *
 * @property \App\Model\Table\ExchangesTable|\Cake\ORM\Association\HasMany $Exchanges
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RateTable extends Table
{

    protected $date;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('rate');
        $this->date = (new Time(Time::now(), 'America/New_York'))->modify('midnight');
    }

    public function getLastExchangeRateData($data)
    {
        $query = $this->find()
            ->where(['trader_id' => $data['id']])
            ->first();
        return $query;
    }

    public function getHighRate($trader_id)
    {
        $query = $this->find()
            ->where(['trader_id' => $trader_id])
            ->where(['last_refreshed >=' => $this->date])
            ->max('exchange_rate');
        return $query;
    }

    public function getLowRate($trader_id)
    {
        $query = $this->find()
            ->where(['trader_id' => $trader_id])
            ->where(['last_refreshed >=' => $this->date])
            ->min('exchange_rate');
        return $query;
    }

    public function deleteRate()
    {
        $this->deleteAll([
            'id >' => 0
        ]);
        return true;
    }

    public function setRowRate()
    {
        $trader = TableRegistry::get('Trader');
        $data = $trader->find('all');

        foreach ($data as $val) {
            $rate = $this->newEntity();
            $rate->trader_id = $val['id'];
            $this->save($rate);
        }
    }
}
