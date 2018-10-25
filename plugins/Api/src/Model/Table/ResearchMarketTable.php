<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/24/2017
 * Time: 22:52
 */

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Scrapper\Scrapper;
use Cake\Utility\Text;
use App\Helpers\SlugHelper;

class ResearchMarketTable extends Table
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

        $this->setTable('research_market');
        $this->hasMany('ResearchCompany', [
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->slugHelper = new SlugHelper();
    }

    /**
     * getResearchMarkets method will return Research Markets.
     *
     * @return array
     */
    public function getResearchMarkets()
    {
        $array = $this->find()
                ->order([
                    'order ASC',
                ])
                ->toArray();

        return $array;
    }

    /**
     * editResearchMarket method will edit Research Markets.
     *
     * @param object $data
     * @param integer $id
     * @return bool
     */
    public function editResearchMarket($data, $id)
    {
        $result = false;
        if (!is_null($data)) {
            $researchMarket = $this->get($id);
            $researchMarket->name = $data['name'];
            $researchMarket->slug = $this->slugHelper->output($data['name']);
            if ($this->save($researchMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * addResearchMarket method will add Research Markets.
     *
     * @param object $data
     * @return bool
     */
    public function addResearchMarket($data)
    {
        $result = false;
        if (!is_null($data)) {
            $researchMarket = $this->newEntity();
            $researchMarket->name = $data['name'];
            $researchMarket->slug = $this->slugHelper->output($data['name']);
            if ($this->save($researchMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * deleteResearchMarket method will remove Research Markets.
     *
     * @param object $id
     * @return bool
     */
    public function deleteResearchMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $researchMarket = $this->get($id);
            if ($this->delete($researchMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * disableResearchMarket method will disable Research Markets.
     *
     * @param object $id
     * @return bool
     */
    public function disableResearchMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $researchMarket = $this->get($id);
            $researchMarket->status = 'disabled';
            if ($this->save($researchMarket)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * enableResearchMarket method will enable Research Markets.
     *
     * @param object $id
     * @return bool
     */
    public function enableResearchMarket($id)
    {
        $result = false;
        if (!is_null($id)) {
            $researchMarket = $this->get($id);
            $researchMarket->status = 'enabled';
            if ($this->save($researchMarket)) {
                $result = true;
            }
        }
        return $result;
    }

}
