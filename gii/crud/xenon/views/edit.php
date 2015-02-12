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

$title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$newLabel = Yii::t('admin', 'New');

$label = $model->isNewRecord ? $newLabel : $model->getLabel();

$this->title = $title.': '.$label.' | '.Yii::$app->name;;;

$this->params['page_title'] = $title;
$this->params['page_subtitle'] = $model->isNewRecord ? '<i class="fa fa-star"></i>&nbsp;'.$label : '<span class="label label-default">'.$model->id.'</span>&nbsp;'.$label;

$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
if ($model->isNewRecord) {
    $this->params['breadcrumbs'][] = $newLabel;
} else {
    $this->params['breadcrumbs'][] = ['label' => '<b>'.$label.'</b>', 'url' => ['view', 'id' => $model->id]];
}

?>


<div class="panel panel-default">

<div class="panel-heading">
    <div class="panel-options">
        <?= '<?= ' ?>Html::a('<i class="fa fa-star-o"></i>', ['create'], ['title' => $newLabel, 'rel' => 'external']) ?>
    </div>
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

<div class="panel-body">

    <?= '<?php ' ?>if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= '<?= ' ?>Yii::t('admin', 'Please fix the errors marked in red below.') ?>
    </div>
    <?= '<?php ' ?>endif ?>

<?php $i = 0; foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        $i++;
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        if ($i % 3 == 0) echo '    <div class="form-group-separator"></div>'."\n\n";
    }
} ?>

</div>

<div class="panel-footer">
    <?= '<?= ' ?>Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
    <?= '<?= ' ?>Html::submitButton( ($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update')).'&nbsp;<i class="fa fa-check"></i>', ['class' => 'btn btn-success']) ?>
</div>

<?= '<?php ' ?>ActiveForm::end(); ?>
</div>