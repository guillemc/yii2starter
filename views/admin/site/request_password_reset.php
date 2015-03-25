<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\admin\PasswordResetRequestForm */

$this->title = Yii::t('admin', 'Request password reset');
$this->params['bodyClass'] = 'login-page';

$requestSuccess = Yii::$app->session->getFlash('request.success', null);
?>

<div class="login-box">
  <div class="login-logo">
      <a href="<?= Yii::getAlias('@web') ?>"><b><?= Yii::$app->name ?></b></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?= Yii::t('admin', 'In order to reset your password, please enter your email address:') ?></p>

    <?php if ($model->hasErrors()): ?>
    <p class="text-danger">
        <i class="fa fa-exclamation-circle"></i> <?= $model->getFirstError('email') ?>
    </p>
    <?php elseif ($requestSuccess): ?>
    <p class="text-success">
        <i class="fa fa-check"></i> <?= Yii::t('admin', 'An email has been sent to {email} with further instructions.', ['email' => '<b>'.$requestSuccess.'</b>']) ?>
    </p>
    <?php elseif (false === $requestSuccess): ?>
    <p class="text-danger">
        <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Email could not be sent. Please try again later, and contact us if the problem persists.') ?>
    </p>
    <?php endif ?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'request-pwd-reset'],
        'fieldConfig' => [
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

      <div class="form-group has-feedback">
        <?= Html::activeTextInput($model, 'email', ['class' => 'form-control', 'autocomplete' => 'off', 'autofocus' => 'autofocus', 'placeholder' => $model->getAttributeLabel('email')]) ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><?= Yii::t('admin', 'Continue') ?></button>
        </div><!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>



  </div><!-- /.login-box-body -->
</div><!-- /.login-box -->