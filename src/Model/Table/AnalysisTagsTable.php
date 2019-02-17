<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnalysisTags Model
 *
 * @property \App\Model\Table\AnalysisTable|\Cake\ORM\Association\BelongsTo $Analysis
 *
 * @method \App\Model\Entity\AnalysisTag get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnalysisTag newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnalysisTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisTag|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalysisTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisTag[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisTag findOrCreate($search, callable $callback = null, $options = [])
 */
class AnalysisTagsTable extends Table
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

        $this->setTable('analysis_tags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Analysis', [
            'foreignKey' => 'analysis_id'
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
                ->allowEmpty('name');

        $validator
                ->dateTime('created_at')
                ->requirePresence('created_at', 'create')
                ->allowEmpty('created_at');

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
        $rules->add($rules->existsIn(['analysis_id'], 'Analysis'));

        return $rules;
    }

    /**
     * 
     * @param type $tags_array
     * @param type $analysis_id
     * 
     */
    public function add($tags_array, $analysis_id)
    {
        $this->deleteAll([
            'analysis_id' => $analysis_id
        ]);
        $entity = $this->newEntity();
        $entity->analysis_id = $analysis_id;
        $entity->name = $tags_array;
        $this->save($entity);
        
        return true;
    }

}
