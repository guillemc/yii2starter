<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\admin\PasswordResetRequestForm */

$this->title = Yii::t('admin', 'Request password reset').' | '.Yii::$app->name;;
$this->params['bodyClass'] = 'login-page';

$requestSuccess = Yii::$app->session->getFlash('request.success', null);
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
						<p><?= Yii::t('admin', 'In order to reset your password, please enter your email address:') ?></p>
					</div>

                    <?= $form->field($model, 'email')->textInput(array('id' => 'email', 'class' => 'form-control input-dark', 'autocomplete' => 'off', 'autofocus' => 'autofocus')) ?>

                    <?php if ($model->hasErrors()): ?>
                    <div class="errors-container">
                        <i class="fa fa-exclamation-circle"></i> <?= $model->getFirstError('email') ?>
                    </div>
                    <?php elseif (null !== $requestSuccess): ?>
                    <div class="errors-container">
                        <?php if ($requestSuccess): ?>
                            <i class="fa fa-check"></i> <?= Yii::t('admin', 'An email has been sent to {email} with further instructions.', ['email' => '<b>'.$requestSuccess.'</b>']) ?>
                        <?php else: ?>
                            <i class="fa fa-exclamation-circle"></i> <?= Yii::t('admin', 'Email could not be sent. Please try again later, and contact us if the problem persists.') ?>
                        <?php endif ?>
                    </div>
                    <?php endif ?>

                    <?php if (!$requestSuccess): ?>
					<div class="form-group">
						<button type="submit" class="btn btn-dark  btn-block text-left">
							<i class="fa fa-arrow-right"></i>
							<?= Yii::t('admin', 'Continue') ?>
						</button>
					</div>
                    <?php endif ?>
				<?php ActiveForm::end(); ?>

			</div>

		</div>

	</div>
