<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Users');

$label = $model->getLabel();

$this->params['page_subtitle'] = '<b>'.$model->id.'</b> '.$label;

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '<b>'.$label.'</b>';

?>

<div class="box box-primary">

<div class="box-body">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'avatar',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
<div class="box-footer">
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;'.Yii::t('admin', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-action' => 'back']) ?>
    <?= Html::a(Yii::t('admin', 'Edit').'&nbsp;<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('admin', 'Delete').'&nbsp;<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
</div>

</div>
