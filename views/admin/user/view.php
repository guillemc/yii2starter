<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$title = Yii::t('app', 'Users');

$label = $model->getLabel();

$this->title = $title.': '.$label.' | '.Yii::$app->name;

$this->params['page_title'] = $title;
$this->params['page_subtitle'] = '<span class="label label-default">'.$model->id.'</span> '.$label;

$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = '<b>'.$label.'</b>';

?>

<div class="panel panel-default">

<div class="panel-body">


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
<div class="panel-footer">
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
