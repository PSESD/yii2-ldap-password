<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace psesd\ldapPassword\components\base;

use yii\base\BootstrapInterface;

/**
 * Bootstrap Run on each request.
 */
class Bootstrap extends \yii\base\Object implements BootstrapInterface
{
    /**
     * @inheritdocs.
     */
    public function bootstrap($app)
    {
    }
}
