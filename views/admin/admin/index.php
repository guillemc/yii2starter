<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'Administrators');
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
        'id' => 'main-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'headerOptions' => ['class' => 'sort-numerical']],
            ['attribute' => 'username', 'format' => 'raw', 'value' => function ($model) {
                return Html::a($model->username, ['update', 'id' => $model->id], ['data-pjax' => 0]);
            }],
            'email:email',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'text-center buttons'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="label label-primary"><i class="fa fa-eye"></i></span>', $url, ['data-pjax' => '0', 'title' => Yii::t('admin', 'View')]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="label label-warning"><i class="fa fa-pencil"></i></span>', $url, ['data-pjax' => '0', 'title' => Yii::t('admin', 'Edit')]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="label label-danger"><i class="fa fa-trash"></i></span>', $url, ['data-pjax' => '0', 'title' => Yii::t('admin', 'Delete'),
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('admin', 'Are you sure you want to delete this user?'),
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<div class="box-footer">
    <?= $this->blocks['buttons'] ?>
</div>

<?php \yii\widgets\Pjax::end() ?>
</div>
