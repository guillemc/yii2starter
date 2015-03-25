<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\admin\PasswordResetRequestForm */

$this->title = Yii::t('admin', 'Password reset');
$this->params['bodyClass'] = 'login-page';

?>

<div class="login-box">
  <div class="login-logo">
      <a href="<?= Yii::getAlias('@web') ?>"><b><?= Yii::$app->name ?></b></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <?php if ($tokenError): ?>
    <p class="text-danger">
        <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Provided token is invalid or has expired. Please {a}try again{/a}.', ['a' => '<a href="'.Url::toRoute(['site/request-password-reset']).'">', '/a' => '</a>']) ?>
    </p>
    <?php else: ?>

    <p class="login-box-msg"><?= Yii::t('admin', 'Welcome, {username}. Please choose your new password:', ['username' => '<b>'.$model->getUsername().'</b>']) ?></p>

    <?php if ($model->hasErrors()): ?>
    <p class="text-danger">
        <i class="fa fa-exclamation-circle"></i> <?= current($model->getFirstErrors()) ?>
    </p>
    <?php endif ?>


    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'pwd-reset'],
        'fieldConfig' => [
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

      <div class="form-group has-feedback">
        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'autocomplete' => 'off', 'autofocus' => 'autofocus', 'placeholder' => $model->getAttributeLabel('password')]) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <?= Html::activePasswordInput($model, 'passwordRepeat', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => $model->getAttributeLabel('passwordRepeat')]) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><?= Yii::t('admin', 'Continue') ?></button>
        </div><!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>
    <?php endif ?>


  </div><!-- /.login-box-body -->
</div><!-- /.login-box -->