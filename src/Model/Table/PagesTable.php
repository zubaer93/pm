<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use Cake\Core\Configure;
use App\Helpers\SlugHelper;

class PagesTable extends Table
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

        $this->slugHelper = new SlugHelper();
        $this->setTable('pages');
    }

    /**
     * getPages method will edit many articles
     *
     * @param array $page_name page name
     * @return array
     */
    public function getPages($page_name = null)
    {
        if (!is_null($page_name)) {
            $result = $this->find()
                ->where(['slug' => $page_name])
                ->first();
        } else {
            $result = $this->find()
                ->toArray();
        }

        return $result;
    }

    /**
     * editPages method will edit many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function editPages($data)
    {
        $result = true;
        if (!is_null($data)) {
            $page = $this->get($data['id']);
            $page->body = $data['body'];
            $page->name = $data['name'];
            $page->slug = $this->slugHelper->pageNameSlug($data['name']);
            $page->position = $data['position'];

            if (!$this->save($page)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * setPage method will save many articles
     *
     * @param array $data articles data
     * @return bool
     */
    public function setPage($data)
    {
        $result = true;
        if (!empty($data)) {
            $page = $this->newEntity();
            $page->name = $data['name'];
            $page->body = $data['body'];
            $page->slug = $this->slugHelper->pageNameSlug($data['name']);
            $page->position = $data['position'];
            if (!$this->save($page)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * deletePage method will delete page
     *
     * @param array $data articles data
     * @return bool
     */
    public function deletePage($id)
    {
        $result = true;
        if (!is_null($id)) {
            $entity = $this->get($id);
            if (!$this->delete($entity)) {
                $result = false;
            }
        }

        return $result;
    }

    public function disablePage($id)
    {
        $result = false;
        if (!is_null($id)) {
            $page = $this->get($id);
            $page->enable = 1;
            if ($this->save($page)) {
                $result = true;
            }
        }
        return $result;
    }

    public function enablePage($id)
    {
        $result = false;
        if (!is_null($id)) {
            $page = $this->get($id);
            $page->enable = 0;
            if ($this->save($page)) {
                $result = true;
            }
        }
        return $result;
    }

}
