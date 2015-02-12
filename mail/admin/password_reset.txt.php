<?php

/* @var $this yii\web\View */
/* @var $user common\models\admin\Admin */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->pwd_reset_token]);
?>
<?= Yii::t('admin', 'Hello {username},', ['username' => $user->username ]) ?>

<?= Yii::t('admin', 'Please follow the link below to reset your password:') ?>

<?= $resetLink ?>