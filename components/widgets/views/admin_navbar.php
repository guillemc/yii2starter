<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">

      <?php if ($this->context->languages): ?>
      <li class="dropdown language-switcher">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-flag"></i>&nbsp;<?= current($this->context->currentLanguage) ?>
        </a>

        <ul class="dropdown-menu languages">
            <?php foreach ($this->context->otherLanguages as $k => $v): ?>
            <li><?= Html::a($v, Url::toRoute(['', 'set-language' => $k]), ['data-method' => 'post']) ?>
            </li>
            <?php endforeach ?>
        </ul>
      </li>
      <?php endif ?>

      <?php if (Yii::$app->user->identity->isRoot()): ?>
      <li>
        <a href="<?= Url::toRoute(['admin/index']) ?>"><i class="fa fa-users"></i>&nbsp;<?= Yii::t('admin', 'Administrators') ?></a>
      </li>
      <?php endif ?>

      <?php if (!Yii::$app->user->isGuest): $avatar = Yii::$app->user->identity->getAvatar(); $username = Html::encode(Yii::$app->user->identity->username); ?>
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <img src="<?= $avatar ?>" class="user-image" alt="User Image"/>
          <!-- hidden-xs hides the username on small devices so only the image appears. -->
          <span class="hidden-xs"><?= $username ?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- The user image in the menu -->
          <li class="user-header">
            <img src="<?= $avatar ?>" class="img-circle" alt="<?= $username ?>" />
            <p>
              <?= $username ?>
              <?php //<small>Last login: </small> ?>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="<?= Url::toRoute(['site/profile']) ?>" class="btn btn-default btn-flat"><?= Yii::t('admin', 'Profile settings') ?></a>
            </div>
            <div class="pull-right">
              <a href="<?= Url::toRoute(['site/logout']) ?>" class="btn btn-default btn-flat" data-method="post"><?= Yii::t('admin', 'Logout') ?></a>
            </div>
          </li>
        </ul>
      </li>
      <?php endif ?>
    </ul>
  </div>
</nav>