<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace psesd\ldapPassword\components\web;

use Yii;

class Controller extends \canis\web\Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
}
?>