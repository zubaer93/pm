<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnalysisType Model
 *
 * @method \App\Model\Entity\AnalysisType get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnalysisType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnalysisType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalysisType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisType findOrCreate($search, callable $callback = null, $options = [])
 */
class AnalysisTypeTable extends Table
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

        $this->setTable('analysis_type');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->allowEmpty('name');

        $validator
            ->allowEmpty('description');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->allowEmpty('created_at');

        return $validator;
    }
}
