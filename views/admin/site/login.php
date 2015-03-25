<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('admin', 'Login');

$this->params['bodyClass'] = 'login-page';

?>

<div class="login-box">
  <div class="login-logo">
      <a href="<?= Yii::getAlias('@web') ?>"><b><?= Yii::$app->name ?></b></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?= Yii::t('admin', 'Please log in to access the admin area') ?></p>

    <?php if ($model->hasErrors()): ?>
    <p class="text-danger">
        <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Invalid username or password.') ?>
    </p>
    <?php endif ?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'login-form'],
        'fieldConfig' => [
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

      <div class="form-group has-feedback">
        <?= Html::activeTextInput($model, 'username', ['class' => 'form-control', 'autocomplete' => 'off', 'autofocus' => 'autofocus', 'placeholder' => $model->getAttributeLabel('username')]) ?>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => $model->getAttributeLabel('password')]) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox">
              <?= Html::activeCheckbox($model, 'rememberMe') ?>
          </div>
        </div><!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><?= Yii::t('admin', 'Log in') ?></button>
        </div><!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>

    <p class="text-right"><?= Html::a(Yii::t('admin', 'Forgot your password?'), ['site/request-password-reset']) ?></p>

  </div><!-- /.login-box-body -->
</div><!-- /.login-box -->