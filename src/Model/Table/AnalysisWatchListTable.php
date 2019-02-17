<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnalysisWatchList Model
 *
 * @property \App\Model\Table\WatchlistGroupTable|\Cake\ORM\Association\BelongsTo $WatchlistGroup
 * @property \App\Model\Table\AnalysisTable|\Cake\ORM\Association\BelongsTo $Analysis
 *
 * @method \App\Model\Entity\AnalysisWatchList get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnalysisWatchList newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnalysisWatchList[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisWatchList|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalysisWatchList patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisWatchList[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisWatchList findOrCreate($search, callable $callback = null, $options = [])
 */
class AnalysisWatchListTable extends Table
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

        $this->setTable('analysis_watch_list');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('WatchlistGroup', [
            'foreignKey' => 'watch_list_group_id'
        ]);
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
        $rules->add($rules->existsIn(['watch_list_group_id'], 'WatchlistGroup'));
        $rules->add($rules->existsIn(['analysis_id'], 'Analysis'));

        return $rules;
    }

    /**
     * 
     * @param type $watch_array
     * @param type $analysis_id
     * @return boolean
     */
    public function add($watch_array, $analysis_id)
    {
        $this->deleteAll([
            'analysis_id' => $analysis_id
        ]);
        
        if (!empty($watch_array)) {
            foreach ($watch_array as $watch) {
                $entity = $this->newEntity();
                $entity->analysis_id = $analysis_id;
                $entity->watch_list_group_id = $watch;
                $this->save($entity);
            }
        }
        return true;
    }

}
