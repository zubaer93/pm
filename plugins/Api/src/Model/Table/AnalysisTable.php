<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Analysis Model
 *
 * @property \App\Model\Table\AppUsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Analysi get($primaryKey, $options = [])
 * @method \App\Model\Entity\Analysi newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Analysi[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Analysi|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Analysi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Analysi[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Analysi findOrCreate($search, callable $callback = null, $options = [])
 */
class AnalysisTable extends Table
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

        $this->setTable('analysis');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('AnalysisNews', [
            'foreignKey' => 'analysis_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AnalysisTags', [
            'foreignKey' => 'analysis_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AnalysisSymbols', [
            'foreignKey' => 'analysis_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AnalysisWatchList', [
            'foreignKey' => 'analysis_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AnalysisType', [
            'foreignKey' => 'type',
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
            ->requirePresence('text', 'create')
            ->notEmpty('text');

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
        $rules->add($rules->existsIn(['user_id'], 'AppUsers'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    public function add($data)
    {
        $entity = $this->newEntity();
        $entity->company_id = $data['company_id'];
        $entity->user_id = $data['user_id'];
        $entity->name = $data['name'];
        $entity->text = $data['text'];
        $entity->type = $data['type'];
        return $this->save($entity);
    }

    public function updateAnalysis($data)
    {
        $result = false;
        if (!is_null($data)) {
            $result = (bool) $this->query()
                ->update()
                ->set($data)
                ->where(['id' => $data['id']])
                ->execute();
        }
        return $result;
    }

    /**
     * findAnalysis method it will get the analysi.
     *
     * @param int $id Analysi ID
     * @return \Cake\ORM\Query
     */
    public function findAnalysi($id)
    {
        return $this->find()
            ->where(['Analysis.id' => $id])
            ->contain([
                'AnalysisNews' => [
                    'News'
                ],
                'AnalysisTags',
                'AnalysisWatchList' => [
                    'WatchlistGroup'
                ],
                'AnalysisType'
            ])
            ->first();
    }
}
