<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('admin', 'Debug info');

$this->params['page_subtitle'] = 'PHP environment variables';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <h3>Server</h3>
    <pre class="pre-scrollable"><?= Html::encode(print_r($_SERVER, true)) ?></pre>

    <h3>Session</h3>
    <pre class="pre-scrollable"><?= Html::encode(print_r($_SESSION, true)) ?></pre>

    <h3>Cookies</h3>
    <pre class="pre-scrollable"><?= Html::encode(print_r($_COOKIE, true)) ?></pre>

</div>
