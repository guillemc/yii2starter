<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = Yii::t('admin', 'Profile settings').' | '.Yii::$app->name;;

$this->params['page_subtitle'] = Yii::t('admin', 'Change your connection details, such as username and password.');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">

<div class="panel-body">

    <?php if (Yii::$app->session->getFlash('profile.success')): ?>
    <div class="alert alert-success">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::t('admin', 'Data successfully saved.') ?>
    </div>
    <?php elseif ($model->hasErrors()): ?>
    <div class="alert alert-danger">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::t('admin', 'Please fix the errors marked in red below.') ?>
    </div>
    <?php endif ?>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'enableClientValidation' => false,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'offset' => 'col-sm-offset-2',
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);


    ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->input('email')->hint(Yii::t('admin', 'Used for password reset.')) ?>

    <?= $form->field($model, 'password')->passwordInput()->hint(Yii::t('admin', 'Leave blank to keep current password.')) ?>

    <?= $form->field($model, 'passwordRepeat')->passwordInput()->hint(Yii::t('admin', 'Leave blank to keep current password.')) ?>


    <div class="form-group-separator"></div>
    <div class="form-group">
        <button class="btn btn-success pull-right" type="submit"><?= Yii::t('admin', 'Save') ?>&nbsp;<i class="fa fa-check"></i></button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>