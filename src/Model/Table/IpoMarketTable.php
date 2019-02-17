<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/24/2017
 * Time: 22:52
 */

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Scrapper\Scrapper;
use Cake\Utility\Text;
use App\Helpers\SlugHelper;

class IpoMarketTable extends Table
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

        $this->setTable('ipo_market');
        $this->hasMany('IpoCompany');

        $this->slugHelper = new SlugHelper();
    }

    /**
     * getIpoMarkets method will return IPO Markets.
     *
     * @return array
     */
    public function getIpoMarkets()
    {
        $array = $this->find()
                ->order([
                    'order ASC',
                ])
                ->toArray();

        return $array;
    }

    /**
     * editIpoMarket method will edit IPO Markets.
     *
     * @param object $data
     * @param integer $id
     * @return bool
     */
    public function editIpoMarket($data, $id)
    {
        $result = false;
        if (!is_null($data)) {
            $ipoMarket = $this->get($id);
            $ipoMarket->name = $data['name'];
            $ipoMarket->slug = $this->slugHelper->output($data['name']);
            if ($this->save($ipoMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * addIpoMarket method will add IPO Markets.
     *
     * @param object $data
     * @return bool
     */
    public function addIpoMarket($data)
    {
        $result = false;
        if (!is_null($data)) {
            $ipoMarket = $this->newEntity();
            $ipoMarket->name = $data['name'];
            $ipoMarket->slug = $this->slugHelper->output($data['name']);
            if ($this->save($ipoMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * deleteIpoMarket method will remove IPO Markets.
     *
     * @param object $id
     * @return bool
     */
    public function deleteIpoMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $ipoMarket = $this->get($id);
            if ($this->delete($ipoMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * disableIpoMarket method will disable IPO Markets.
     *
     * @param object $id
     * @return bool
     */
    public function disableIpoMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $market = $this->get($id);
            $market->status = 'disabled';
            if ($this->save($market)) {
                $result = true;
            }
        }
        return $result;
    }
    /**
     * enableIpoMarket method will disable IPO Markets.
     *
     * @param object $id
     * @return bool
     */
    public function enableIpoMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $market = $this->get($id);
            $market->status = 'enabled';
            if ($this->save($market)) {
                $result = true;
            }
        }
        return $result;
    }

}
