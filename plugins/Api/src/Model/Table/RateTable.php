<?php

namespace Api\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;


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
        $trader = TableRegistry::get('Api.Trader');
        $data = $trader->find('all');

        foreach ($data as $val) {
            $rate = $this->newEntity();
            $rate->trader_id = $val['id'];
            $this->save($rate);
        }
    }

}
