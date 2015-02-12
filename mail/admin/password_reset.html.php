<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\admin\Admin */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->pwd_reset_token]);
?>
<div class="password-reset">
    <p><?= Yii::t('admin', 'Hello {username},', ['username' => $user->username ]) ?></p>

    <p><?= Yii::t('admin', 'Please follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>