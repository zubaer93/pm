<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Validation\Validator;


class SearchSummaryTable extends Table
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

        $this->setTable('search_summary');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Import All users list in search summary page
     */
    public function user()
    {
        $usersObj = TableRegistry::get('Api.AppUsers');
        $users = $usersObj->find()
            ->select(['avatar', 'first_name', 'last_name', 'username', 'id'])
            ->toArray();

        foreach ($users as $key => $user) {
            $data = [
                'avatar' => $user->avatar,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'id' => $user->id

            ];
            $this->singleUser($data);
        }
    }

    /**
     * Insert individual user on aftersave
     * @param $user
     */
    public function singleUser($user)
    {
        if (empty($user)) {
            return;
        }

        $avatar = $user['avatar'];

        $search_tag = array($user['first_name'] . ' ' . $user['last_name'], $user['username']);
      
        $params = array('username' => $user['username'], 'first_name' => $user['first_name'], 'last_name' => $user['last_name']);
        $data = [
            'content_type' => 'user',
            'content_id' => $user['id'],
            'search_tag' => implode('@||@', $search_tag),
            'params' => json_encode($params),
        ];
        $summary = $this->find()->where(['content_id' => $user['id'], 'content_type' => 'user'])->first();
   
        if (empty($summary)) {
            $summary = $this->newEntity();
        }
               
        $summary = $this->patchEntity($summary, $data);
        
        $this->save($summary);
    }

    public function trader()
    {
        $traderObj = TableRegistry::get('Api.Trader');
        $traders = $traderObj->find()->where()->toArray();
        foreach ($traders as $trader) {
            $data = [
                'from_currency_code' => $trader->from_currency_code,
                'from_currency_name' => $trader->from_currency_name,
                'to_currency_code' => $trader->to_currency_code,
                'to_currency_name' => $trader->to_currency_name,
                'exchange_rate' => $trader->exchange_rate,
                'id' => $trader->id

            ];
            $this->singleTrade($data);

        }
    }

    public function singleTrade($trader)
    {
        if (empty($trader)) {
            return;
        }

        $search_tag = [
            $trader['from_currency_code'],
            $trader['from_currency_name'],
            $trader['to_currency_code'],
            $trader['to_currency_name']
        ];
        $params = [
            'from_currency_code' => $trader['from_currency_code'],
            'to_currency_code' => $trader['to_currency_code'],
            'exchange' => $trader['exchange_rate']
        ];
        $data = [
            'content_type' => 'trader',
            'content_id' => $trader['id'],
            'search_tag' => implode('@||@', $search_tag),
            'params' => json_encode($params),
        ];
        $summary = $this->find()->where(['content_id' => $trader['id'], 'content_type' => 'trader'])->first();
        if (empty($summary)) {
            $summary = $this->newEntity();
        }
        $summary = $this->patchEntity($summary, $data);
        $this->save($summary);
    }

    /**
     * Import all compay information in search summary table
     */
    public function company()
    {
        $companyObj = TableRegistry::get('Api.Companies');
        $companies = $companyObj->find()
            ->select(['id', 'name', 'symbol', 'Exchanges.name', 'Exchanges.country_id'])
            ->contain(['Exchanges'])
            ->toArray();
        foreach ($companies as $i => $company) {
            $data = ['name' => $company->name,
                'symbol' => $company->symbol,
                'exchange_id' => $company->exchange_id,
                'id' => $company->id,
                'exchange_name' => $company->exchange->name,
                'country_id' => $company->exchange->country_id
            ];
            $this->singleCompany($data);
        }
    }

    public function singleCompany($company)
    {
        if (!empty($data)) {
            return;
        } else {
            $countryObj = TableRegistry::get('Api.Countries');
            $market = $countryObj->get($company['country_id'])->market;
            $search_tag = [
                $company['name'],
                $company['symbol'],
            ];
            $params = [
                'name' => $company['name'],
                'symbol' => $company['symbol'],
                'exchange' => $company['exchange_name'],
                'lang' => $market
            ];
            $data = [
                'content_type' => 'company',
                'content_id' => $company['id'],
                'search_tag' => implode('@||@', $search_tag),
                'params' => json_encode($params),
            ];
            $summary = $this->find()->where(['content_id' => $company['id'], 'content_type' => 'company'])->first();
            if (empty($summary)) {
                $summary = $this->newEntity();
            }
            $summary = $this->patchEntity($summary, $data);
            $this->save($summary);
        }
    }

    public function search($q, $limit = 10)
    {
        $items = [];
        if (preg_match('/$/', $q['phrase'])) {
            $q['phrase'] = str_replace('$', '', $q['phrase']);
        }

        if (preg_match('/@/', $q['phrase'])) {
            $q['phrase'] = str_replace('@', '', $q['phrase']);
        }

        $data = $this->find()
            ->where(['search_tag LIKE' => '%' . $q['phrase'] . '%'])
            ->limit($limit)
            ->order(['content_type' => 'ASC']);

        return $this->formatSearch($data);
    }

    public function formatSearch($data)
    {
        $items = [];
        foreach ($data as $i => $item) {
            $params = json_decode($item->params, true);
            if ($item->content_type == 'company') {
                $items['company'][] = [
                    'type' => 'company',
                    'name' => $params['name'],
                    'symbol' => $params['symbol'],
                    'exchange' => $params['exchange'],
                    'url' => Router::url(['_name' => 'symbol', 'stock' => $params['symbol'], 'lang' => $params['lang']])
                ];
            } elseif ($item->content_type == 'trader') {
                $items['trader'][] = [
                    'type' => 'trader',
                    'from_currency_code' => $params['from_currency_code'],
                    'to_currency_code' => $params['to_currency_code'],
                    'exchange' => $params['exchange']
                ];
            } else {
                $items['user'][] = [
                    'type' => 'user',
                    'fullname' => $params['first_name'] . ' ' . $params['last_name'],
                    'username' => $params['username'],
                    'icon' => '',
                ];
            }
        }
        return $items;
    }
}
