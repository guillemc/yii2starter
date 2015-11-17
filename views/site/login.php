<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Url::base();

?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please fill out the following fields to login:</p>

<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-md-3\">{input}</div>\n<div class=\"col-md-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-md-1 control-label'],
    ],
]); ?>

<?= $form->field($model, 'username') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'rememberMe')->checkbox([
    'template' => "<div class=\"col-md-offset-1 col-md-3\"><div class=\"checkbox\"><label>{input} {labelTitle}</label></div></div>\n<div class=\"col-md-8\">{error}</div>",
    'label' => 'Remember me on this computer',
]) ?>

<div class="form-group">
    <div class="col-md-offset-1 col-md-11">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>


