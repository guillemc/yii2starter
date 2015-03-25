<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\admin\Admin */
/* @var $m app\models\admin\ProfileForm */

$this->title = Yii::t('admin', 'Administrators');
$newLabel = Yii::t('admin', 'New', ['g' => 'm']); //gender: 'm' or 'f'

$label = $model->isNewRecord ? $newLabel : $model->getLabel();

$this->params['page_subtitle'] = $model->isNewRecord ? '<i class="fa fa-star"></i>&nbsp;'.$label : '<b>'.$model->id.'</b>&nbsp;'.$label;

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if ($model->isNewRecord) {
    $this->params['breadcrumbs'][] = $newLabel;
} else {
    $this->params['breadcrumbs'][] = ['label' => '<b>'.$label.'</b>', 'url' => ['view', 'id' => $model->id]];
}

?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::t('admin', 'Please fix the errors marked in red below.') ?>
    </div>
<?php elseif (Yii::$app->session->getFlash('data.saved')): ?>
    <div class="alert alert-success">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::t('admin', 'Data successfully saved.') ?>
    </div>
<?php endif ?>

<div class="box box-primary">

<div class="box-header">
    <p class="text-right"><?= Html::a('<i class="fa fa-star-o"></i>', ['create'], ['title' => $newLabel, 'rel' => 'external']) ?></p>
</div>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-10 col-lg-6',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

<div class="box-body">

    <?= $form->field($m, 'username')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($m, 'email')->textInput(['maxlength' => 128])->hint(Yii::t('admin', 'Used for password reset.')) ?>

    <hr>

    <?php if ($model->isNewRecord): ?>
    <?= $form->field($m, 'password')->passwordInput() ?>
    <?= $form->field($m, 'passwordRepeat')->passwordInput() ?>
    <?php else: ?>
    <?= $form->field($m, 'password')->passwordInput()->hint(Yii::t('admin', 'Leave blank to keep current password.')) ?>
    <?= $form->field($m, 'passwordRepeat')->passwordInput()->hint(Yii::t('admin', 'Leave blank to keep current password.')) ?>
    <?php endif ?>
</div>

<div class="box-footer">
    <div class="row"><div class="col-sm-10 col-sm-offset-2">
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
    <?= Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success']) ?>
    </div></div>
</div>
<?php ActiveForm::end(); ?>
</div>
