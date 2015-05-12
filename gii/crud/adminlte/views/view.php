<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;

$label = $model->getLabel();

$this->params['page_title'] = $this->title;
$this->params['page_subtitle'] = '<b>'.$model->id.'</b> '.$label;

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '<b>'.$label.'</b>';

?>

<div class="box box-primary">

<div class="box-body">


    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        $spec = $generator->getDetailColumnSpec($column, $format);
        if ($spec) {
            echo "            " . $spec . "\n";
            continue;
        }
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

</div>
<div class="box-footer">
    <?= '<?= ' ?>Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
    <?= '<?= ' ?>Html::a(Yii::t('admin', 'Edit').'&nbsp;<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= '<?= ' ?>Html::a(Yii::t('admin', 'Delete').'&nbsp;<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
</div>

</div>

