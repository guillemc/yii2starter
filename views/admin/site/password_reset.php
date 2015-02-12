<?php
//use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\admin\PasswordResetRequestForm */

$this->title = Yii::t('admin', 'Password reset').' | '.Yii::$app->name;;
$this->params['bodyClass'] = 'login-page';

?>
	<div class="login-container">

		<div class="row">

			<div class="col-sm-6">

				<?php $form = ActiveForm::begin([
                    'options' => ['id' => 'login', 'class' => 'login-form'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}",
                        'labelOptions' => ['class' => 'control-label'],
                    ],
                ]); ?>
                    <?php if (!$tokenError): ?>
					<div class="login-header">
						<a href="#" class="logo">
							<span><?= Yii::$app->name ?></span>
						</a>
						<p><?= Yii::t('admin', 'Welcome, {username}. Please choose your new password:', ['username' => '<b>'.$model->getUsername().'</b>']) ?></p>
					</div>

                    <?= $form->field($model, 'password')->passwordInput(array('id' => 'pwd', 'class' => 'form-control input-dark', 'autocomplete' => 'off', 'autofocus' => 'autofocus')) ?>
                    <?= $form->field($model, 'passwordRepeat')->passwordInput(array('id' => 'pwd-repeat', 'class' => 'form-control input-dark', 'autocomplete' => 'off')) ?>

                    <?php if ($model->hasErrors()): ?>
                    <div class="errors-container">
                        <i class="fa fa-exclamation-circle"></i> <?= current($model->getFirstErrors()) ?>
                    </div>
                    <?php endif ?>
                    <div class="form-group">
						<button type="submit" class="btn btn-dark  btn-block text-left">
							<i class="fa fa-arrow-right"></i>
							<?= Yii::t('admin', 'Continue') ?>
						</button>
					</div>
                    <?php else: ?>
                    <div class="login-header">
						<a href="#" class="logo">
							<span><?= Yii::$app->name ?></span>
						</a>
						<p><?= Yii::t('admin', 'Password reset') ?></p>
					</div>
                    <div class="errors-container">
                        <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Provided token is invalid or has expired. Please {a}try again{/a}.', ['a' => '<a href="'.Url::toRoute(['site/request-password-reset']).'">', '/a' => '</a>']) ?>
                    </div>
                    <?php endif ?>
				<?php ActiveForm::end(); ?>

			</div>

		</div>

	</div>
