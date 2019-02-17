<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ApiUsersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->table('api_users');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

}
