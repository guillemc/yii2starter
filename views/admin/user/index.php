<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$addNewLabel = Yii::t('admin', 'Add new', ['g' => 'm']); //gender: 'm' or 'f'

$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('buttons');
?>
<?= Html::a('<i class="fa fa-plus"></i>&nbsp;&nbsp;'.$addNewLabel, ['create'], ['class' => 'btn btn-info', 'data-pjax' => '0']) ?>
<?php $this->endBlock() ?>

<?php if (Yii::$app->session->getFlash('data.saved')): ?>
<div class="alert alert-success">
<button data-dismiss="alert" class="close" type="button">×</button>
<?= Yii::t('admin', 'Data successfully saved.') ?>
</div>
<?php endif ?>

<div class="box box-primary">
<?php \yii\widgets\Pjax::begin() ?>

<div class="box-header">
    <?= $this->render('//partials/pager', ['pageSize' => $pageSize]) ?>
    <?= $this->blocks['buttons'] ?>
</div>

<div class="box-body">

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

</div>

<div class="box-footer">
    <?= $this->blocks['buttons'] ?>
</div>

<?php \yii\widgets\Pjax::end() ?>
</div>
