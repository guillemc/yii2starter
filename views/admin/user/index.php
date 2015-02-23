<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = Yii::t('app', 'Users');
$addNewLabel = Yii::t('admin', 'Add new');

$this->title = $title.' | '.Yii::$app->name;;;

$this->params['breadcrumbs'][] = $title;

$this->beginBlock('buttons');
?><div class="btn-toolbar">

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

    <?php if (Yii::$app->session->getFlash('data.saved')): ?>
    <div class="alert alert-success">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::t('admin', 'Data successfully saved.') ?>
    </div>
    <?php endif ?>

    <?php echo $this->blocks['buttons'] ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'headerOptions' => ['class' => 'sort-numerical']],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->username, ['update', 'id' => $model->id]);
                },
            ],            
            'email:email',
            'avatar',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'sort-numerical'],
                'filter' => false,
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'sort-numerical'],
                'filter' => false,
                'contentOptions' => ['class' => 'text-center'],
            ],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class' => 'text-center']],
        ],
    ]); ?>


    <?php echo $this->blocks['buttons'] ?>


</div>

<?php \yii\widgets\Pjax::end() ?>
</div> 