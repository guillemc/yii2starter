<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$addNewLabel = Yii::t('admin', 'Add new', ['g' => 'm']); //gender: 'm' or 'f'

$this->params['page_title'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('buttons');
<?= '?>' ?>
<?= '<?= '?>Html::a('<i class="fa fa-plus"></i>&nbsp;&nbsp;'.$addNewLabel, ['create'], ['class' => 'btn btn-info', 'data-pjax' => '0']) ?>
<?= '<?php ' ?>$this->endBlock() ?>

<?= '<?php ' ?>if (Yii::$app->session->getFlash('data.saved')): ?>
<div class="alert alert-success">
<button data-dismiss="alert" class="close" type="button">×</button>
<?= '<?= ' ?>Yii::t('admin', 'Data successfully saved.') ?>
</div>
<?= '<?php ' ?>endif ?>

<div class="box box-primary">
<?= '<?php ' ?>\yii\widgets\Pjax::begin() ?>

<div class="box-header">
    <?= '<?= ' ?>$this->render('//partials/pager', ['pageSize' => $pageSize]) ?>
    <?= '<?= ' ?>$this->blocks['buttons'] ?>
</div>

<div class="box-body">

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            //['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'headerOptions' => ['class' => 'sort-numerical']],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        if ($column->name == 'id') continue;
        $spec = $generator->getGridColumnSpec($column);
        if ($spec) {
            echo '            '.$spec."\n";
            continue;
        }
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class' => 'text-center']],
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>

<div class="box-footer">
   <?= '<?= ' ?>$this->blocks['buttons'] ?>
</div>

<?= '<?php ' ?>\yii\widgets\Pjax::end() ?>
</div>
