<?php

namespace Api\Model\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

class PrivateTable extends Table
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

        $this->setTable('private');
        $this->setPrimaryKey('id');
        
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserInvite', [
            'foreignKey' => 'invite_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->allowEmpty('name')
                ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
                ->allowEmpty('slug');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function setPrivate($data, $user_id)
    {
        $result = true;
        if (isset($data)) {
            $private = $this->newEntity();
            $private->user_id = $user_id;
            $private->name = $data->request->getData('post_name');
            $private->slug = Text::slug($data->request->getData('post_name'), '-');
            try{
            if (!$this->save($private)) {
                $result = false;
            }
            } catch (\Exception $e){
                $result = false;
            }
        }
        return ['id' => $private->id, 'result' => $result];
    }

    public function update($data)
    {
        $result = true;
        if (isset($data)) {
            $id = $data['id'];
            $privatesTable = $this->newEntity();
            $private = $this->get($id); // Return Private with id 
            $private->name = $data['post_name'];
            $private->slug = Text::slug($data['post_name'], '-');
            if (!$this->save($private)) {
                $result = false;
            }
        }
        return ['result' => $result];
    }

}
