<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('app', 'Users');
$newLabel = Yii::t('admin', 'New', ['g' => 'm']); //gender: 'm' or 'f'

$label = $model->isNewRecord ? $newLabel : $model->getLabel();

$this->params['page_subtitle'] = $model->isNewRecord ? '<i class="fa fa-star"></i>&nbsp;'.$label : '<b>'.$model->id.'</b>&nbsp;'.$label;

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if ($model->isNewRecord) {
    $this->params['breadcrumbs'][] = $newLabel;
} else {
    $this->params['breadcrumbs'][] = ['label' => '<b>'.$label.'</b>', 'url' => ['view', 'id' => $model->id]];
}

echo newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=fancybox]',
]);
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
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
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
]); ?>

    <div class="box-body">

    <?= $form->field($model, 'username')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 128]) ?>

    <hr>

    <?php if (!$model->isNewRecord): ?>
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <p class="text-info"><?= Yii::t('admin', 'Leave blank to keep the current password.') ?></p>
        </div>
    </div>
    <?php endif ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'passwordRepeat')->passwordInput(['maxlength' => 128]) ?>

    <hr>

    <?php if ($model->avatar): ?>
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <a href="<?= $model->getImageUrl() ?>" rel="fancybox"><img alt="avatar" src="<?= $model->getImageUrl('thumb') ?>"></a>
            <div class="checkbox"><?= Html::activeCheckbox($model, 'avatarRemove') ?></div>
        </div>
    </div>
    <?php endif ?>
    <?= $form->field($model, 'avatarUpload')->fileInput() ?>

    </div>

    <div class="box-footer">
        <div class="row"><div class="col-sm-10 col-sm-offset-2">
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
        <?= Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Save')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create and return') : Yii::t('admin', 'Save and return')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success', 'onclick' => "this.form['continue'].value='0'; return true;"]) ?>
        <input type="hidden" name="continue" value="1" />
        </div></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
