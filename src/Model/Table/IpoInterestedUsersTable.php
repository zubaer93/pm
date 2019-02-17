<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class IpoInterestedUsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('ipo_interested_users');
        $this->belongsTo('IpoCompany');
        $this->belongsTo('AppUsers');
    }

    /**
     * setIpoInterestedUser method will save user's interesting in company
     *
     * @param $data
     * @return bool
     */
    public function setIpoInterestedUser($data)
    {
        $result = false;
        if (!empty($data)  && !$this->exists($data)) {
            $ipoInterestedUsers = $this->newEntity();
            $ipoInterestedUsers->app_user_id = $data['app_user_id'];
            $ipoInterestedUsers->ipo_company_id = $data['ipo_company_id'];

            if ($this->save($ipoInterestedUsers)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * deleteIpoInterestedUser method will delete user's interesting in company
     *
     * @param $id
     * @return bool
     */
    public function deleteIpoInterestedUser($id)
    {
        $result = false;
        if (!is_null($id)) {
            $entity = $this->get($id);
            if ($this->delete($entity)) {
                $result = true;
            }
        }
        return $result;
    }
}