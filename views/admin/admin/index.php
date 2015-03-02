<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = Yii::t('admin', 'Administrators');
$addNewLabel = Yii::t('admin', 'Add new', ['g' => 'm']); //gender: 'm' or 'f'

$this->title = $title.' | '.Yii::$app->name;

$this->params['page_title'] = $title;
$this->params['breadcrumbs'][] = $title;

$this->beginBlock('buttons');
?>
<div class="btn-toolbar">

<?= Html::a($addNewLabel.'&nbsp;&nbsp;<i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-info', 'data-pjax' => '0']) ?>

</div>
<?php $this->endBlock() ?>


<div class="panel panel-default">
<?php \yii\widgets\Pjax::begin() ?>

<div class="panel-heading">
    <div class="pull-right">
        <a role="button" data-toggle="dropdown" class="dropdown-toggle" href="#" title="<?= Yii::t('admin', 'Items per page') ?>">
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <ul role="menu" class="dropdown-menu">
        <li><a href="#"><?= Yii::t('admin', 'Items per page') ?></a></li>
        <li class="divider"></li>
        <?php foreach (Yii::$app->params['admin.page.sizes'] as $n): ?>
        <li<?php if ($n == $pageSize) echo ' class="active"' ?>><a href="#" data-pager="<?= $n ?>"><?= $n ?></a></li>
        <?php endforeach ?>
        </ul>
    </div>
</div>

<div class="panel-body">
    <?php echo $this->blocks['buttons'] ?>

    <?= GridView::widget([
        'id' => 'main-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'headerOptions' => ['class' => 'sort-numerical']],
            ['attribute' => 'username', 'format' => 'raw', 'value' => function ($model) {
                return Html::a($model->username, ['update', 'id' => $model->id]);
            }],
            'email:email',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class' => 'text-center']],
        ],
    ]); ?>

    <?php echo $this->blocks['buttons'] ?>

</div>

<?php \yii\widgets\Pjax::end() ?>
</div>
