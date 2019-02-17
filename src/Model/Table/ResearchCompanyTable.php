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

class ResearchCompanyTable extends Table
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

        $this->setTable('research_company');
        $this->belongsTo('ResearchMarket');
        $this->hasMany('ResearchInterestedUsers', [
            'foreignKey' => 'research_company_id',
            'joinType' => 'INNER'
        ]);

        $this->slugHelper = new SlugHelper();
    }

    /**
     * getResearchCompanies method will return Research Companies.
     *
     * @return array
     */
    public function getResearchCompanies()
    {
        $array = $this->find()
            ->order([
                'order ASC',
            ])
            ->toArray();

        return $array;
    }

    /**
     * editResearchCompany method will edit Research Companies.
     *
     * @param object $data
     * @param integer $id
     * @return bool
     */
    public function editResearchCompany($data, $id)
    {
        $result = false;
        if (!is_null($data)) {
            $researchCompany = $this->get($id);

            $researchCompany->name = $data['name'];
            $researchCompany->slug = $this->slugHelper->output($data['name']);
            $researchCompany->research_market_id = $data['research_market'];
            $researchCompany->about = $data['about'];
            if ($this->save($researchCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * addResearchCompany method will add Research Companies.
     *
     * @param object $data
     * @return bool
     */
    public function addResearchCompany($data)
    {
        $result = false;
        if (!is_null($data)) {
            $researchCompany = $this->newEntity();
            $researchCompany->name = $data['name'];
            $researchCompany->slug = $this->slugHelper->output($data['name']);
            $researchCompany->research_market_id = $data['research_market'];
            $researchCompany->about = $data['about'];
            if ($this->save($researchCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * deleteResearchCompany method will remove Research Companies.
     *
     * @param object $id
     * @return bool
     */
    public function deleteResearchCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $researchCompany = $this->get($id);
            if ($this->delete($researchCompany)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * disableResearchCompany method will disable Research Companies.
     *
     * @param object $id
     * @return bool
     */
    public function disableResearchCompany($id)
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
     * ResearchCompany method will disable IPO Companies.
     *
     * @param object $id
     * @return bool
     */
    public function enableResearchCompany($id)
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