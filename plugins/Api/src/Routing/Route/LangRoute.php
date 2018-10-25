<?php
/**
 * Created by PhpStorm.
 * User: Tonmoy
 * Date: 7/26/2018
 * Time: 5:48 PM
 */

namespace Api\Routing\Route;

use Cake\Core\Configure;
use Cake\Routing\Route\DashedRoute;
use Cake\Utility\Hash;

class LangRoute extends DashedRoute
{
    /**
     * Regular expression for `lang` route element.
     *
     * @var string
     */
    protected static $_langRegEx = null;

    /**
     * Constructor for a Route.
     *
     * @param string $template Template string with parameter placeholders
     * @param array $defaults Array of defaults for the route.
     * @param string $options Array of parameters and additional options for the Route
     *
     * @return void
     */
    public function __construct($template, $defaults = [], array $options = [])
    {
        $pos = explode('/', $template);
        if ($pos[1] == 'api') {
            array_splice($pos, 2, 0, [':lang']);
            $template = implode('/', $pos);
        }

        $options['inflect'] = 'dasherize';
        $options['persist'][] = 'lang';

        if (!array_key_exists('lang', $options)) {
            if (self::$_langRegEx === null &&
                $langs = Configure::read('I18n.languages')
            ) {
                self::$_langRegEx = implode('|', array_keys(Hash::normalize($langs)));
            }
            $options['lang'] = self::$_langRegEx;
        }
        parent::__construct($template, $defaults, $options);
    }
}