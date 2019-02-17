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

class IpoCompanyTable extends Table
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

        $this->setTable('ipo_company');
        $this->belongsTo('IpoMarket');
        $this->hasMany('IpoInterestedUsers', [
            'foreignKey' => 'ipo_company_id',
            'joinType' => 'INNER'
        ]);

        $this->slugHelper = new SlugHelper();
    }

    /**
     * getIpoCompanies method will return IPO Companies.
     *
     * @return array
     */
    public function getIpoCompanies()
    {
        $array = $this->find()
                ->order([
                    'order ASC',
                ])
                ->toArray();

        return $array;
    }

    /**
     * editIpoCompany method will edit IPO Companies.
     *
     * @param object $data
     * @param integer $id
     * @return bool
     */
    public function editIpoCompany($data, $id)
    {
        $result = false;
        if (!is_null($data)) {
            $ipoCompany = $this->get($id);

            $ipoCompany->name = $data['name'];
            $ipoCompany->slug = $this->slugHelper->output($data['name']);
            $ipoCompany->ipo_market_id = $data['ipo_market'];
            $ipoCompany->about = $data['about'];
            if ($this->save($ipoCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * addIpoCompany method will add IPO Companies.
     *
     * @param object $data
     * @return bool
     */
    public function addIpoCompany($data)
    {
        $result = false;
        if (!is_null($data)) {
            $ipoCompany = $this->newEntity();
            $ipoCompany->name = $data['name'];
            $ipoCompany->slug = $this->slugHelper->output($data['name']);
            $ipoCompany->ipo_market_id = $data['ipo_market'];
            $ipoCompany->about = $data['about'];
            if ($this->save($ipoCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * deleteIpoCompany method will remove IPO Companies.
     *
     * @param object $id
     * @return bool
     */
    public function deleteIpoCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $ipoCompany = $this->get($id);
            if ($this->delete($ipoCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * disableIpoCompany method will disable IPO Companies.
     *
     * @param object $id
     * @return bool
     */
    public function disableIpoCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $company = $this->get($id);
            $company->status = 'disabled';
            if ($this->save($company)) {
                $result = true;
            }
        }
        return $result;
    }
    /**
     * disableIpoCompany method will disable IPO Companies.
     *
     * @param object $id
     * @return bool
     */
    public function enableIpoCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $company = $this->get($id);
            $company->status = 'enabled';
            if ($this->save($company)) {
                $result = true;
            }
        }
        return $result;
    }

}
