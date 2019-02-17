<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * KeyPeople Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\KeyPerson get($primaryKey, $options = [])
 * @method \App\Model\Entity\KeyPerson newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\KeyPerson[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\KeyPerson|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\KeyPerson patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\KeyPerson[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\KeyPerson findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class KeyPeopleTable extends Table
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

        $this->setTable('key_people');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => []
        ]);

        $this->belongsTo('Companies');
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
            ->allowEmpty('title');

        $validator
            ->allowEmpty('age');

        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('photo', 'fileAboveMinHeight', [
            'rule' => ['isAboveMinHeight', 180],
            'message' => 'This image should at least be 180px high',
            'provider' => 'upload'
        ]);

        $validator->add('photo', 'fileAboveMinWidth', [
            'rule' => ['isAboveMinWidth', 286],
            'message' => 'This image should at least be 286px wide',
            'provider' => 'upload'
        ]);

        $validator->add('photo', 'fileSuccessfulWrite', [
            'rule' => 'isSuccessfulWrite',
            'message' => 'This upload failed',
            'provider' => 'upload'
        ]);

        $validator
            ->add('photo', 'file', [
                'rule' => [
                    'mimeType', [
                        'image/jpeg',
                        'image/png'
                    ]
                ],
                'message' => 'Just is allowed JPG or PNG'
            ])
            ->allowEmpty('photo');

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
}
