<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

app\assets\XenonAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="page-body <?php if (isset($this->params['bodyClass'])) echo $this->params['bodyClass'] ?>">
<?php $this->beginBody() ?>


    <?php /* = $this->render('_settings_pane') */ ?>

    <div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
        <!-- Add "fixed" class to make the sidebar fixed always to the browser viewport. -->
		<!-- Adding class "toggle-others" will keep only one menu item open at a time. -->
		<!-- Adding class "collapsed" collapse sidebar root elements and show only icons. -->
		<div class="sidebar-menu fixed toggle-others">
			<div class="sidebar-menu-inner">
				<header class="logo-env">

					<!-- logo -->
					<div class="logo">
						<a href="<?= Url::toRoute('site/index') ?>" class="logo-expanded">
							<?php echo Yii::$app->name;  /* <img src="<?= Yii::getAlias('@web/images/logo.png') ?>" width="80" alt="logo-80" /> */ ?>
						</a>
						<a href="<?= Url::toRoute('site/index') ?>" class="logo-collapsed">
							<?php echo Yii::$app->name; /* <img src="<?= Yii::getAlias('@web/images/logo-collapsed.png') ?>" width="40" alt="logo-40" /> */ ?>
						</a>
					</div>

					<!-- This will toggle the mobile menu and will be visible only on mobile devices -->
					<div class="mobile-menu-toggle visible-xs">
						<a href="#" data-toggle="user-info-menu">
							<i class="fa-plus-circle"></i>
						</a>
						<a href="#" data-toggle="mobile-menu">
							<i class="fa-bars"></i>
						</a>
					</div>
                    <?php /*
					<!-- This will open the popup with user profile settings, you can use for any purpose, just be creative -->
					<div class="settings-icon">
						<a href="#" data-toggle="settings-pane" data-animate="true">
							<i class="linecons-cog"></i>
						</a>
					</div>
                    */ ?>
				</header>
                <?= app\components\widgets\AdminMenu::widget(['items' => require(Yii::getAlias('@app/config/backend-menu.php'))]) ?>
			</div>
		</div>

		<div class="main-content">

            <?= app\components\widgets\AdminNavbar::widget() ?>

            <?php if (!isset($this->params['page_title']) || false !== $this->params['page_title']): ?>
            <div class="page-title">
                <div class="title-env">
                    <h1 class="title"><?= isset($this->params['page_title']) ? $this->params['page_title'] : $this->title ?></h1>
                    <?php if (!empty($this->params['page_subtitle'])): ?><p class="description"><?= $this->params['page_subtitle'] ?></p><?php endif ?>
                </div>

                <?php if (isset($this->params['breadcrumbs'])): ?>
                <div class="breadcrumb-env">
                    <?= Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => '<i class="fa-home"></i>&nbsp;'.Yii::t('admin', 'Home'),
                            'url' => ['site/index'],
                        ],
                        'links' => $this->params['breadcrumbs'],
                        'encodeLabels' => false,
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                    ]) ?>
                </div>
                <?php endif ?>
            </div>
            <?php endif ?>

            <?= $content ?>

			<!-- Main Footer -->
			<!-- Choose between footer styles: "footer-type-1" or "footer-type-2" -->
			<!-- Add class "sticky" to  always stick the footer to the end of page (if page contents is small) -->
			<!-- Or class "fixed" to  always fix the footer to the end of page -->
			<footer class="main-footer sticky footer-type-1">
				<div class="footer-inner">
					<!-- Add your copyright text here -->
					<div class="footer-text">
						&copy; <?= date('Y') ?>
						<strong><?= Yii::$app->name ?></strong>
					</div>

					<!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
					<div class="go-up">
						<a href="#" rel="go-top">
							<i class="fa-angle-up"></i>
						</a>
					</div>
				</div>
			</footer>

		</div>

	</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
