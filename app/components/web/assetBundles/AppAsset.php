<?php
/**
 * @link http://psesd.org
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license https://raw.githubusercontent.com/PSESD/yii2-ldap-password/master/LICENSE
 */

namespace psesd\ldapPassword\components\web\assetBundles;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@psesd/ldapPassword/assets';
    public $css = [
        'css/app.css',
    ];
    public $js = [
        'js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
