<?php

namespace Api\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;

class SimulationsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->belongsTo('Stocks');
        $this->belongsTo('Companies');
    }

    public function getSimulation($userId, $market, $subDomain = null)
    {
        $query = $this->find()
            ->where(['Simulations.user_id' => $userId])
            ->contain(['Companies' => function ($q) use ($market) {
                return $q->autoFields(false)
                    ->contain(['Exchanges' => function ($q) use ($market) {
                        return $q->autoFields(false)
                            ->contain(['Countries' => function ($q) use ($market) {
                                return $q->autoFields(false)
                                    ->where(['Countries.market' => $market]);
                            }]);
                    }]);
                }
            ]);
        return $query;

    }
}
