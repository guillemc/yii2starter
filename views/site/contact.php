<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ContactForm */

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Url::base();

?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

<div class="alert alert-success">
    Thank you for contacting us. We will respond to you as soon as possible.
</div>

<?php else: ?>

<p>
    Please fill out the following form to contact us.
</p>

<div class="row">
    <div class="col-lg-5">
        <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'contact-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-3',
                        'offset' => 'col-sm-offset-3',
                        'wrapper' => 'col-sm-9',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]);
        ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'subject') ?>
            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
            <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-sm-6">{image}</div><div class="col-sm-6">{input}</div></div>',
            ]) ?>
            <div class="row">
                <div class="col-sm-offset-3 col-sm-9"><?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?></div>
            </div></div>
        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>
</div>

<?php endif; ?>