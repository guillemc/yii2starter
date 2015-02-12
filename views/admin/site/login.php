<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('admin', 'Login').' | '.Yii::$app->name;;

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

					<div class="login-header">
						<a href="#" class="logo">
							<span><?= Yii::$app->name ?></span>
						</a>

						<p><?= Yii::t('admin', 'Please log in to access the admin area') ?></p>
					</div>

                    <?= $form->field($model, 'username')->textInput(array('id' => 'username', 'class' => 'form-control input-dark', 'autocomplete' => 'off', 'autofocus' => 'autofocus')) ?>

					<?= $form->field($model, 'password')->passwordInput(array('id' => 'passwd', 'class' => 'form-control input-dark', 'autocomplete' => 'off')) ?>

                    <?php if ($model->hasErrors()): ?>
                    <div class="errors-container">
                        <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Invalid username or password.') ?>
                    </div>
                    <?php endif ?>

					<div class="form-group">
						<button type="submit" class="btn btn-dark  btn-block text-left">
							<i class="fa fa-lock"></i>
							<?= Yii::t('admin', 'Log in') ?>
						</button>
					</div>


					<div class="login-footer">
                        <?= Html::a(Yii::t('admin', 'Forgot your password?'), ['site/request-password-reset']) ?>

						<!-- <div class="info-links">
							<a href="#">ToS</a> -
							<a href="#">Privacy Policy</a>
						</div> -->

					</div>

				<?php ActiveForm::end(); ?>

			</div>

		</div>

	</div>
