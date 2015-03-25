<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;

$this->beginContent('@psesd/ldapPassword/views/layouts/frame.php');
NavBar::begin([
    'brandLabel' => Yii::$app->params['siteName'],
    'brandUrl' => ['default/index'],
    'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
]);
// echo Nav::widget([
//     'options' => ['class' => 'nav navbar-nav navbar-right'],
//     'items' => [
//     ],
// ]);

$topRightItems = [];
// $topRightItems[] = DeferredNavItem::widget([]);
if (Yii::$app->user->isGuest) {
    $topRightItems[] = ['label' => '<span title="Sign In" class="menu-icon fa fa-sign-in"></span>', 'url' => ['/user/login'], 'linkOptions' => []];
} else {
    $topRightItems[] = ['label' => '<span class="menu-icon pull-left fa fa-sign-out" title="Sign Out"></span> '.Yii::$app->user->identity->first_name.'', 'url' => ['/user/logout'], 'linkOptions' => ['title' => 'Sign Out']];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav pull-right'],
    'encodeLabels' => false,
    'items' => $topRightItems,
]);

NavBar::end();
echo Html::beginTag('div', ['class' => 'container']);
echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'encodeLabels' => false,
]);
if (($success = Yii::$app->session->getFlash('success', false, true))) {
    echo Html::tag('div', $success, ['class' => 'alert alert-success']);
}
if (($error = Yii::$app->session->getFlash('error', false, true))) {
    echo Html::tag('div', $error, ['class' => 'alert alert-danger']);
}
echo $content;
echo Html::endTag('div');
$this->endContent();
