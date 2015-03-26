<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license https://raw.githubusercontent.com/PSESD/yii2-ldap-password/master/LICENSE
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