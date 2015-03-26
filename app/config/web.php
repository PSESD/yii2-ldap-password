<?php
$config = include __DIR__ . DIRECTORY_SEPARATOR .  'base.php';

$config['controllerNamespace'] = 'psesd\ldapPassword\controllers';
if (isset($base['components']['redis'])) {
	$config['components']['session'] = [
	    'class' => 'yii\redis\Session',
	    'timeout' => '4000', 
	];
}
$config['components']['urlManager'] = [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => null, // disable in production
    'rules' => [
        // a standard rule mapping '/' to 'site/index' action
        '' => 'default/index',
        '<controller:\w+>' => '<controller>/index>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ],
];
$config['components']['assetManager'] = [
    'linkAssets' => false,
];
$config['components']['user'] = [
    'identityClass' => \psesd\ldapPassword\models\LdapUser::className(),
];
return $config;