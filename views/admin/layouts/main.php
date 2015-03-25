<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

app\assets\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title.' | '.Yii::$app->name) ?></title>
    <?php $this->head() ?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="<?= is_array($this->params['bodyClass']) ? implode(' ', $this->params['bodyClass']) : $this->params['bodyClass'] ?>">
<?php $this->beginBody() ?>

<div class="wrapper">

    <header class="main-header">
    <a href="<?= Yii::getAlias('@web') ?>" class="logo" rel="external"><b><?= Yii::$app->name ?></b></a>
    <?= app\components\widgets\AdminNavbar::widget() ?>
    </header>

    <aside class="main-sidebar">
    <section class="sidebar">
        <?= app\components\widgets\AdminMenu::widget(['items' => require(Yii::getAlias('@app/config/backend-menu.php'))]) ?>
    </section>
    </aside>


    <div class="content-wrapper" id="main-content">
        <?php if (!isset($this->params['page_title']) || false !== $this->params['page_title']): ?>
        <section class="content-header">
          <h1>
            <?= isset($this->params['page_title']) ? $this->params['page_title'] : $this->title ?>
            <?php if (!empty($this->params['page_subtitle'])): ?><small><?= $this->params['page_subtitle'] ?></small><?php endif ?>
          </h1>

          <?php if (isset($this->params['breadcrumbs'])) echo Breadcrumbs::widget([
            'homeLink' => [
                'label' => '<i class="fa fa-home"></i>&nbsp;'.Yii::t('admin', 'Home'),
                'url' => ['site/index'],
            ],
            'links' => $this->params['breadcrumbs'],
            'encodeLabels' => false,
            'tag' => 'ol',
            'options' => ['class' => 'breadcrumb'],
          ]) ?>
        </section>
        <?php endif ?>
        <section class="content">
            <?= $content ?>
        </section>
    </div>

    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          <a href="#main-content" rel="go-top"><i class="fa fa-angle-up"></i></a>
        </div>
        &copy; <?= date('Y') ?>
		<strong><?= Yii::$app->name ?></strong>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
