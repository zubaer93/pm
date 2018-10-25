<?php

namespace Api\Helpers;

use Cake\Utility\Text;

/**
 * @class SlugHelper
 *
 *
 * This Class is for slug creating
 */
class SlugHelper
{

    /**
     * output method generating slug
     *
     * @param string $title title to be slugged.
     * @param object $entity entity for which slug is creating
     * @return string
     */
    public function output($title, $entity = null)
    {
        $slug = strtolower(Text::slug($title));
        if (!is_null($entity)) {
            $check = $entity->find()
                ->where(['slug' => $slug])
                ->first();
        }

        if (!empty($check)) {
            $id = $entity->find()
                ->select(['id' => 'MAX(id)'])
                ->first();

            $slug .= !empty($id) ? '-' . $id->id : '';
        }

        return $slug;
    }

    /**
     * output method generating slug
     *
     * @param string $title title to be slugged.
     * @param object $entity entity for which slug is creating
     * @return string
     */
    public static function pageNameSlug($name)
    {
        $slug = strtolower(Text::slug($name));

        return $slug;
    }

}
