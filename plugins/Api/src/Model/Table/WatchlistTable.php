<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Watchlist Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Watchlist get($primaryKey, $options = [])
 * @method \App\Model\Entity\Watchlist newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Watchlist[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Watchlist patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WatchlistTable extends Table
{

    const JMD = 'JMD';
    const USD = 'USD';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('watchlist');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('WatchlistGroup', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    // public function buildRules(RulesChecker $rules)
    // {
    //     $rules->add($rules->existsIn(['user_id'], 'Users'));
    //     $rules->add($rules->existsIn(['company_id'], 'Companies'));

    //     return $rules;
    // }

    public function updateWatchListItem($data, $userId, $Language = null, $subDomain = null)
    {
        $result = false;
        if ($data['type'] == 'update') {
            $this->Companies->addCompanyWatchList($data['company_id'], $Language);
            $entity = $this->newEntity();
            $entity->user_id = $userId;
            $entity->company_id = $data['company_id'];
            $entity->subdomain = $subDomain;
            if (isset($data['group_id'])) {
                $entity->group_id = $data['group_id'];
            }
            if ($this->save($entity)) {
                $simbol = TableRegistry::get('Api.Stocks');
                $query = $simbol->find()
                    ->where(['company_id' => $entity->company_id])
                    ->order(['Stocks.id DESC'])
                    ->first();
                if ($Language == self::JMD) {
                    $stockInfo = $simbol->getStockInformationLocal($query->symbol, $entity->company_id);
                } else {
                    $stockInfo = $simbol->getStockInformation($query->symbol);
                }
                $simulation = TableRegistry::get('Api.Simulations');
                $add = $simulation->newEntity();
                $add->watchlist_id = $entity->id;
                $add->user_id = $entity->user_id;
                $add->company_id = $entity->company_id;
                $add->price = Hash::get($stockInfo, 'info.1. open') + Hash::get($stockInfo, 'info.6.price_change');
                $add->simulations_symbols = $query->symbol;
                if ($simulation->save($add))
                    $result = true;
            }
            return $result;
        } elseif ($data['type'] == 'delete') {
            return $this->deleteAll([
                'user_id' => $userId,
                'company_id' => $data['company_id']
            ]);
        }
    }

    public function deleteWatchlist($group_id, $userId)
    {
        $query = $this->find()->where(['user_id' => $userId])
                ->where(['group_id' => $group_id])
                ->first();
        if ($query) {
            return $this->deleteAll([
                'user_id' => $userId,
                'group_id' => $group_id
            ]);
        }
    }

    public function verifyCompanyInWatchlist($userId, $data, $subDomain = null)
    {
        $array = ['Watchlist.subdomain' => $subDomain];
        if (is_null($subDomain)) {
            $array = ['Watchlist.subdomain IS' => $subDomain];
        }

        return (bool) $this->find()
            ->where([
                'Watchlist.user_id' => $userId,
                'Watchlist.company_id' => $data['company_id']
            ])
            ->where($array)
            ->first();
    }

    public function getWatchlist($userId, $market, $subDomain = null)
    {
        $array = ['Watchlist.subdomain' => $subDomain];
        if (is_null($subDomain)) {
            $array = ['Watchlist.subdomain IS' => $subDomain];
        }

        $query = $this->find()
            ->where(['Watchlist.user_id' => $userId])
            ->where($array)
            ->contain([
                'Companies' => function ($q) use ($market) {
                    return $q->autoFields(false)
                        ->where(['Companies.enable !=' => 1])
                        ->contain(['Exchanges' => function ($q) use ($market) {
                            return $q->autoFields(false)
                                ->contain(['Countries' => function ($q) use ($market) {
                                    return $q->autoFields(false)
                                        ->where(['Countries.market' => $market]);
                                    }]);
                            }]);
            }
        ]);

        $watchlist = [];
        foreach ($query as $key => $item) {
            $watchlist[$key]['symbol'] = $item['company']['symbol'];
            $watchlist[$key]['company_id'] = $item['company']['id'];
        }
        return $watchlist;
    }

    public function getWatchlistAll($userId, $market)
    {
        $query = $this->find()
            ->where(['Watchlist.user_id' => $userId])
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

        $watchlist = [];
        foreach ($query as $key => $item) {
            $watchlist[$key]['symbol'] = $item['company']['symbol'];
            $watchlist[$key]['company_id'] = $item['company']['id'];
        }
        return $watchlist;
    }

    public function getWatchlistGroup($userId, $market, $group_id)
    {   
        $query = $this->find()
            ->where(['Watchlist.user_id' => $userId])
            ->where(['Watchlist.group_id' => $group_id])
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

        $watchlist = [];
        foreach ($query as $key => $item) {
            $watchlist[$key]['symbol'] = $item['company']['symbol'];
            $watchlist[$key]['company_id'] = $item['company']['id'];
            $watchlist[$key]['group_id'] = $item['group_id'];
        }
        return $watchlist;
    }

    /**
     * getStocksAlerts method it will return all possible users to send an alert
     *
     * @param array $companyIds Company Ids
     * @return Cake\ORM\Query
     */
    public function getStocksAlerts($companyIds, $kind)
    {
        return $this->find()
            ->where(['company_id IN' => $companyIds])
            ->contain([
                'Companies',
                'AppUsers' => function($q) use ($kind) {
                    return $q->matching(
                        'TimeAlerts', function($q) use ($kind) {
                            return $q->where([
                                'when_happens' => 1,
                                'kind' => $kind
                            ]);
                    });
                }
            ]);
    }

    public function addWatchlistAndSimulations($userId, $data, $Language){
        $entity = $this->newEntity();
        $entity->user_id = $userId;
        $entity->company_id = $data['company_id'];
        if (isset($data['group_id'])) {
            $entity->group_id = $data['group_id'];
        }
        //pr($entity);exit;
        if ($this->save($entity)) {
            $company = TableRegistry::get('Api.Companies');
            $symbol=TableRegistry::get('Api.Stocks');
            $query = $company->find()
                ->where(['id' => $entity->company_id])
                ->order(['id DESC'])
                ->first();
            if ($Language == self::JMD) {
                $stockInfo = $symbol->getStockInformationLocal($query->symbol, $entity->company_id);
            } else {
                $stockInfo = $symbol->getStockInformation($query->symbol);
            }

            $simulation = TableRegistry::get('Api.Simulations');
            $add = $simulation->newEntity();
            $add->watchlist_id = $entity->id;
            $add->user_id = $entity->user_id;
            $add->company_id = $entity->company_id;
            $add->price = Hash::get($stockInfo, 'info. open') + Hash::get($stockInfo, 'info.price_change');
            $add->simulations_symbols = $query->symbol;

            $simulation->save($add);
            return 1;
        }
    }

}
