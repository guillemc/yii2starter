<?php
/* @var $this yii\web\View */
$this->title = Yii::t('admin', 'Home');
$this->params['page_title'] = false;
?>

<?php if (Yii::$app->session->get('password_reset')): ?>
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?= Yii::t('admin', 'Password successfully reset.') ?>
</div>
<?php endif ?>