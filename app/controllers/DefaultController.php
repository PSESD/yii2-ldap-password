<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace psesd\ldapPassword\controllers;

use Yii;

class DefaultController extends \psesd\ldapPassword\components\web\Controller
{
	/**
     * The landing page for the application.
     */
    public function actionIndex()
    {
        Yii::$app->response->view = 'index';
    }
}
?>