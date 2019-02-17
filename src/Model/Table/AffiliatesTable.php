<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Affiliates Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsToMany $Companies
 *
 * @method \App\Model\Entity\Affiliate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Affiliate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Affiliate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Affiliate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Affiliate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Affiliate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Affiliate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AffiliatesTable extends Table
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

        $this->setTable('affiliates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'logo' => []
        ]);

        $this->belongsToMany('Companies');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('address');

        $validator
            ->allowEmpty('website');

        $validator
            ->allowEmpty('description');

        $validator
            ->date('date_of_incorporation')
            ->allowEmpty('date_of_incorporation');

        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('logo', 'fileAboveMinHeight', [
            'rule' => ['isAboveMinHeight', 150],
            'message' => 'This image should at least be 180px high',
            'provider' => 'upload'
        ]);

        $validator->add('logo', 'fileAboveMinWidth', [
            'rule' => ['isAboveMinWidth', 250],
            'message' => 'This image should at least be 286px wide',
            'provider' => 'upload'
        ]);

        $validator->add('logo', 'fileSuccessfulWrite', [
            'rule' => 'isSuccessfulWrite',
            'message' => 'This upload failed',
            'provider' => 'upload'
        ]);

        $validator
            ->add('logo', 'file', [
                'rule' => [
                    'mimeType', [
                        'image/jpeg',
                        'image/png'
                    ]
                ],
                'message' => 'Just is allowed JPG or PNG'
            ])
            ->allowEmpty('logo');


        return $validator;
    }
}
