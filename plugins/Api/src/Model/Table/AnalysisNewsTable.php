<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnalysisNews Model
 *
 * @property \App\Model\Table\NewsTable|\Cake\ORM\Association\BelongsTo $News
 * @property \App\Model\Table\AnalysisTable|\Cake\ORM\Association\BelongsTo $Analysis
 *
 * @method \App\Model\Entity\AnalysisNews get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnalysisNews newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnalysisNews[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisNews|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalysisNews patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisNews[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnalysisNews findOrCreate($search, callable $callback = null, $options = [])
 */
class AnalysisNewsTable extends Table
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

        $this->setTable('analysis_news');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('News', [
            'foreignKey' => 'news_id'
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
        $rules->add($rules->existsIn(['news_id'], 'News'));
        $rules->add($rules->existsIn(['analysis_id'], 'Analysis'));

        return $rules;
    }

    /**
     * 
     * @param type $news
     * @param type $analysis_id
     * 
     */
    public function add($news_array, $analysis_id)
    {
        $this->deleteAll([
            'analysis_id' => $analysis_id
        ]);
        if (!empty($news_array)) {
            foreach ($news_array as $news) {
                $entity = $this->newEntity();
                $entity->analysis_id = $analysis_id;
                $entity->news_id = $news;
                $this->save($entity);
            }
        }

        return true;
    }

}
