<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\gii\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$newLabel = Yii::t('admin', 'New', ['g' => 'm']); //gender: 'm' or 'f'

$label = $model->isNewRecord ? $newLabel : $model->getLabel();

$this->params['page_title'] = $this->title;
$this->params['page_subtitle'] = $model->isNewRecord ? '<i class="fa fa-star"></i>&nbsp;'.$label : '<b>'.$model->id.'</b>&nbsp;'.$label;

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
if ($model->isNewRecord) {
    $this->params['breadcrumbs'][] = $newLabel;
} else {
    $this->params['breadcrumbs'][] = ['label' => '<b>'.$label.'</b>', 'url' => ['view', 'id' => $model->id]];
}

?>

<?= '<?php ' ?>if (<?= $generator->saveMultiple ? '$hasError' : '$model->hasErrors()' ?>): ?>
<div class="alert alert-danger">
<button data-dismiss="alert" class="close" type="button">×</button>
<?= '<?= ' ?>Yii::t('admin', 'Please fix the errors marked in red below.') ?>
</div>
<?= '<?php ' ?>elseif (Yii::$app->session->getFlash('data.saved')): ?>
<div class="alert alert-success">
<button data-dismiss="alert" class="close" type="button">×</button>
<?= '<?= ' ?>Yii::t('admin', 'Data successfully saved.') ?>
</div>
<?= '<?php ' ?>endif ?>

<div class="box box-primary">

<div class="box-header">
    <p class="text-right"><?= '<?= ' ?>Html::a('<i class="fa fa-star-o"></i>', ['create'], ['title' => $newLabel, 'rel' => 'external']) ?></p>
</div>

<?= '<?php ' ?>$form = ActiveForm::begin([
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
]); ?>

<div class="box-body">

<?php $i = 0; foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        $i++;
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        if ($i % 3 == 0) echo '    <hr>'."\n\n";
    }
} ?>

</div>

<div class="box-footer">
    <div class="row"><div class="col-sm-10 col-sm-offset-2">
    <?= '<?= ' ?>Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
    <?= '<?= ' ?>Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Save')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success']) ?>
    <?php if ($generator->saveAndReturn): ?><?= '<?= ' ?>Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create and return') : Yii::t('admin', 'Save and return')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success', 'onclick' => "this.form['continue'].value='0'; return true;"]) ?>
    <input type="hidden" name="continue" value="1" /><?php endif ?>
    </div></div>
</div>

<?= '<?php ' ?>ActiveForm::end(); ?>
</div>

