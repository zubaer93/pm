<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ApiRequestsTable extends Table
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

        $this->table('api_requests');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }
}
