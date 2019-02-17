<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * FinancialStatement Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\FinancialStatementFilesTable|\Cake\ORM\Association\HasMany $FinancialStatementFiles
 *
 * @method \App\Model\Entity\FinancialStatement get($primaryKey, $options = [])
 * @method \App\Model\Entity\FinancialStatement newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FinancialStatement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatement|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinancialStatement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatement[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatement findOrCreate($search, callable $callback = null, $options = [])
 */
class FinancialStatementTable extends Table
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

        $this->setTable('financial_statement');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('FinancialStatementFiles', [
            'foreignKey' => 'financial_statement_id'
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
                ->allowEmpty('title');

        $validator
                ->allowEmpty('description');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    /**
     * setStatement method will save many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function setStatement($data)
    {
        $statement = $this->newEntity();
        $statement->title = $data['title'];
        $statement->description = $data['title'];
        $statement->company_id = $data['company_id'];
        $statement->modified_at = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

        $statement = $this->save($statement);
        if ($statement) {

            $FinancialStatementFiles = TableRegistry::get('FinancialStatementFiles');
            if (isset($data['file'])) {
                  $FinancialStatementFiles->setStatementAdmin($data, $statement['id']);
            } else {
                foreach ($data['file_name'] as $val) {
                    $FinancialStatementFiles->setStatement($val, $statement['id']);
                }
            }
        }
    }
    /**
     * editStatement method will save many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function editStatement($data,$id)
    {

        $statement = $this->get($id);
        $statement->title = $data['title'];
        $statement->description = $data['title'];
        $statement->company_id = $data['company_id'];
        $statement->modified_at = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

        $statement = $this->save($statement);
        if ($statement) {

            $FinancialStatementFiles = TableRegistry::get('FinancialStatementFiles');
            if (isset($data['edit_file_name_admin'])) {
                  $FinancialStatementFiles->editStatementAdmin($data, $statement['id']);
            } else {
                foreach ($data['file_name'] as $val) {
                    $FinancialStatementFiles->setStatement($val, $statement['id']);
                }
            }
        }
    }

    /**
     * 
     * @param type $title
     * @return boolean
     */
    public function checkStatement($title)
    {
        $query = $this->find()
            ->where(['title' => $title])
            ->first();

        if (is_null($query)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @param type $company_id
     */
    public function getInfo($company_id)
    {
        $financialStatement = $this->FinancialStatement->find('all')
            ->where(['company_id' => $company_id])
            ->first();
    }

    /**
     * getFinancialStatements method it wil get the financial statements
     *
     * @param string $symbol Company Symbol
     * @param string $language Current Language
     * @return Cake\ORM\Query
     */
    public function getFinancialStatements($symbol, $language)
    {
        return $this->find()
            ->contain(['Companies' => function ($q) use ($symbol, $language) {
                return $q->where(['Companies.symbol' => $symbol])
                    ->contain(['Exchanges' => function ($q) use ($language) {
                        return $q->contain(['Countries' => function ($q) use ($language) {
                                return $q->where(['Countries.market' => $language]);
                        }]);
                }]);
        }])
        ->order(['FinancialStatement.id' => 'DESC']);
    }

}
