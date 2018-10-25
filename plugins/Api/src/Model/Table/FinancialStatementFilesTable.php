<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * FinancialStatementFiles Model
 *
 * @property \App\Model\Table\FinancialStatementTable|\Cake\ORM\Association\BelongsTo $FinancialStatement
 *
 * @method \App\Model\Entity\FinancialStatementFile get($primaryKey, $options = [])
 * @method \App\Model\Entity\FinancialStatementFile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FinancialStatementFile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatementFile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinancialStatementFile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatementFile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialStatementFile findOrCreate($search, callable $callback = null, $options = [])
 */
class FinancialStatementFilesTable extends Table
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

        $this->setTable('financial_statement_files');
//        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FinancialStatement', [
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
                ->allowEmpty('file_path');

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
        $rules->add($rules->existsIn(['financial_statement_id'], 'FinancialStatement'));

        return $rules;
    }

    /**
     * 
     * @param type $data
     */
    public function setStatement($file_path, $financial_statement_id)
    {
        $result = true;
        $statement = $this->newEntity();
        $statement->file_path = $file_path;
        $statement->financial_statement_id = $financial_statement_id;
        $statement->modified_at = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

        $this->save($statement);
    }

    /**
     * 
     * @param type $data
     */
    public function setStatementAdmin($data, $financial_statement_id)
    {
        foreach ($data['file'] as $val) {
            $bool = \Api\Model\Service\UploadFile::uploadFile($val);
            if ($bool) {
                $result = true;
                $statement = $this->newEntity();
                $statement->file_path = $val['name'];
                $statement->financial_statement_id = $financial_statement_id;
                $statement->modified_at = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

                $this->save($statement);
            }
        }
    }

    /**
     * 
     * @param type $data
     */
    public function editStatementAdmin($data, $financial_statement_id)
    {

        $query = $this->find()
                ->where(['financial_statement_id' => $financial_statement_id]);
        foreach ($query as $key => $val) {
            if (!isset($data['file_name_admin'])) {
                unlink(Configure::read('Users.financial.path') . $val['file_path']);
                $this->delete($val);
            } else {
                if (array_search($val['file_path'], $data['file_name_admin']) === false) {
                    unlink(Configure::read('Users.financial.path') . $val['file_path']);
                    $this->delete($val);
                }
            }
        }

        if (isset($data['file'])) {
            foreach ($data['file'] as $val) {
                $bool = \Api\Model\Service\UploadFile::uploadFile($val);
                if ($bool) {
                    $result = true;
                    $statement = $this->newEntity();
                    $statement->file_path = $val['name'];
                    $statement->financial_statement_id = $financial_statement_id;
                    $statement->modified_at = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");
                    $this->save($statement);
                }
            }
        }
    }

    /**
     * 
     * @param type $data
     */
    public function deleteFiles($financial_statement_id)
    {

        $result = true;
        $statements = $this->find()
                ->where(['financial_statement_id' => $financial_statement_id]);
        foreach ($statements as $statement) {
            try {
                unlink(Configure::read('Users.financial.path') . $statement['file_path']);
            } catch (\Exception $e) {
                continue;
            }
        }
        return TRUE;
    }

}
