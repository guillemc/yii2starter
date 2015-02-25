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

$title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$addNewLabel = Yii::t('admin', 'Add new', ['g' => 'm']); //gender: 'm' or 'f'

$this->title = $title.' | '.Yii::$app->name;

$this->params['breadcrumbs'][] = $title;

$this->beginBlock('buttons');
<?= '?>' ?>
<div class="btn-toolbar">

<?= '<?= '?>Html::a($addNewLabel.'&nbsp;&nbsp;<i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-info', 'data-pjax' => '0']) ?>

</div>
<?= '<?php ' ?>$this->endBlock() ?>



<div class="panel panel-default">
<?= '<?php ' ?>\yii\widgets\Pjax::begin() ?>

<div class="panel-heading">
    <div class="pull-right">
        <a role="button" data-toggle="dropdown" class="dropdown-toggle" href="#" title="<?= '<?= ' ?>Yii::t('admin', 'Items per page') ?>">
          <i class="fa fa-ellipsis-v"></i>
        </a>
        <ul role="menu" class="dropdown-menu">
        <li><a href="#"><?= '<?= ' ?>Yii::t('admin', 'Items per page') ?></a></li>
        <li class="divider"></li>
        <?= '<?php ' ?>foreach (Yii::$app->params['admin.page.sizes'] as $n): ?>
        <li<?= '<?php ' ?>if ($n == $pageSize) echo ' class="active"' ?>><a href="#" data-pager="<?= '<?= ' ?>$n ?>"><?= '<?= ' ?>$n ?></a></li>
        <?= '<?php ' ?>endforeach ?>
        </ul>
    </div>
</div>

<div class="panel-body">

    <?= '<?php ' ?>if (Yii::$app->session->getFlash('data.saved')): ?>
    <div class="alert alert-success">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= '<?= ' ?>Yii::t('admin', 'Data successfully saved.') ?>
    </div>
    <?= '<?php ' ?>endif ?>

    <?= '<?php ' ?>echo $this->blocks['buttons'] ?>


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


    <?= '<?php ' ?>echo $this->blocks['buttons'] ?>


</div>

<?= '<?php ' ?>\yii\widgets\Pjax::end() ?>
</div>
