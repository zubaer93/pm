<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Behavior;
use Cake\Utility\Hash;
use Cake\ORM\Table;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class PartnersTable extends Table
{

    const USD = 'USD';
    const JMD = 'JMD';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('partners');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
                ->allowEmpty('id', 'create');
        $validator
                ->requirePresence('name', 'create')
                ->notEmpty('name', 'Please fill this field');

        $validator
                ->requirePresence('subdomain', 'create')
                ->notEmpty('subdomain', 'Please fill this field');
        $validator
                ->allowEmpty('logo')
                ->add('avatar', [
                    'uploadError' => [
                        'rule' => 'uploadError',
                        'message' => __('The logo upload failed.'),
                        'on' => function ($context)
                        {
                            if (is_string($context['data']['avatar'])) {
                                return strpos($context['data']['avatar'], 'http') < 0;
                            }
                        }
                    ],
                    'mimeType' => [
                        'rule' => ['mimeType', ['image/png', 'image/jpg', 'image/jpeg']],
                        'message' => __('Please upload images only (png, jpg).'),
                        'on' => function ($context)
                        {
                            if (is_string($context['data']['avatar'])) {
                                return strpos($context['data']['avatar'], 'http') < 0;
                            }
                        }
                    ],
                    'fileSize' => [
                        'rule' => ['fileSize', '<=', '2MB'],
                        'message' => __('Logo image must be less than 2MB.'),
                        'on' => function ($context)
                        {
                            if (is_string($context['data']['avatar'])) {
                                return strpos($context['data']['avatar'], 'http') < 0;
                            }
                        }
                    ],
        ]);
        $validator
                ->requirePresence('main_color', 'create')
                ->notEmpty('main_color', 'Please fill this field');

        $validator
                ->requirePresence('main_border_color', 'create')
                ->notEmpty('main_border_color', 'Please fill this field');
        return $validator;
    }

    /**
     * getAll method will return brokers.
     *
     * @return array
     */
    public function __getPartnersInfo($id, $market = 'USD')
    {
        return $this->find()
                        ->where(['id' => $id])
                        ->where(['market' => $market])
                        ->first();
    }

    public function disablePartners($id)
    {
        $result = false;
        if (!is_null($id)) {
            $partner = $this->get($id);
            $partner->enable = 1;
            if ($this->save($partner)) {
                $result = true;
            }
        }
        return $result;
    }

    public function enablePartners($id)
    {
        $result = false;
        if (!is_null($id)) {
            $partner = $this->get($id);
            $partner->enable = 0;
            if ($this->save($partner)) {
                $result = true;
            }
        }
        return $result;
    }

    public function deletePartner($id)
    {
        $result = true;
        if (!is_null($id)) {
            $partner = $this->get($id);
            $this->deleteImg($partner->logo_path);
            if (!$this->delete($partner)) {
                $result = false;
            }
        }
        return $result;
    }

    public function deleteImg($filename)
    {
        $filename = Configure::read('Partners.image.fullpath') . $filename;
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function addPartner($data)
    {

        $result = true;
        if (!is_null($data)) {
            $parter = $this->newEntity();
            $parter->name = $data['name'];
            $parter->subdomain = $data['subdomain'];
            $parter->main_color = $data['main_color'];
            $parter->main_border_color = $data['main_border_color'];
            $parter->logo_path = $this->uploadFile($data);

            if (!$this->save($parter)) {
                $result = false;
            }
        }
        return $result;
    }

    public function uploadFile($data)
    {
        $path = $data['image']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $filename = time() . '.' . $ext;

        $fullpath = Configure::read('Partners.image.fullpath') . $filename;

        if (!file_exists(Configure::read('Partners.image.fullpath'))) {
            mkdir(Configure::read('Partners.image.fullpath'), 0777, true);
        }

        if (!move_uploaded_file($data['image']['tmp_name'], $fullpath)) {
            return '';
        }

        return $filename;
    }

    /**
     * editPartner method will edit many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function editPartner($data)
    {

        $result = false;
        if (!is_null($data)) {
            $parter = $this->get($data['id']);
            $parter->name = $data['name'];
            $parter->subdomain = $data['subdomain'];
            $parter->main_color = $data['main_color'];
            $parter->main_border_color = $data['main_border_color'];
            if (isset($data['image']['name']) && !empty($data['image']['name'])) {
                $this->deleteImg($parter->logo_path);
                $parter->logo_path = $this->uploadFile($data);
            }
            if ($this->save($parter)) {
                $result = true;
            }
        }

        return $result;
    }

}
