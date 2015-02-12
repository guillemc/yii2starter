<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<nav class="navbar user-info-navbar"  role="navigation"><!-- User Info, Notifications and Menu Bar -->

    <!-- Left links for user info navbar -->
    <ul class="user-info-menu left-links list-inline list-unstyled">

        <li class="hidden-sm hidden-xs">
            <a href="#" data-toggle="sidebar">
                <i class="fa-bars"></i>
            </a>
        </li>

        <?php if ($this->context->languages): ?>
        <li class="dropdown hover-line language-switcher">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?= current($this->context->currentLanguage) ?>
            </a>

            <ul class="dropdown-menu languages">
                <?php foreach ($this->context->otherLanguages as $k => $v): ?>
                <li><?= Html::a($v, Url::toRoute(['', 'set-language' => $k]), ['data-method' => 'post']) ?>
                </li>
                <?php endforeach ?>
            </ul>
        </li>
        <?php endif ?>

    </ul>


    <?php if (!Yii::$app->user->isGuest): ?>
    <ul class="user-info-menu right-links list-inline list-unstyled">
        <?php if (Yii::$app->user->identity->isRoot()): ?>
        <li>
            <a href="<?= Url::toRoute(['admin/index']) ?>" title="<?= Yii::t('admin', 'Administrators') ?>"><i class="fa fa-users"></i></a>
        </li>
        <?php endif ?>
        <li class="dropdown user-profile">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= Yii::$app->user->identity->getAvatar() ?>" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
                <span>
                    <?= Html::encode(Yii::$app->user->identity->username) ?>
                    <i class="fa-angle-down"></i>
                </span>
            </a>

            <ul class="dropdown-menu user-profile-menu list-unstyled">
                <li>
                    <a href="<?= Url::toRoute(['site/profile']) ?>">
                        <i class="fa-wrench"></i>
                        <?= Yii::t('admin', 'Profile settings') ?>
                    </a>
                </li>
                <li class="last">
                    <a href="<?= Url::toRoute(['site/logout']) ?>" data-method="post">
                        <i class="fa-lock"></i>
                        <?= Yii::t('admin', 'Logout') ?>
                    </a>
                </li>
            </ul>
        </li>

        <!--
        <li>
            <a href="#" data-toggle="chat">
                <i class="fa-comments-o"></i>
            </a>
        </li>
        -->
    </ul>
    <?php endif ?>

</nav>