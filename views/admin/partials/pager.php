
<div class="pull-right">
    <a role="button" data-toggle="dropdown" class="dropdown-toggle" href="#" title="<?= Yii::t('admin', 'Items per page') ?>">
      <?= Yii::t('admin', 'Items per page') ?>&nbsp;<i class="fa fa-ellipsis-v"></i>
    </a>
    <ul role="menu" class="dropdown-menu">
    <?php foreach (Yii::$app->params['admin.page.sizes'] as $n): ?>
    <li<?php if ($n == $pageSize) echo ' class="active"' ?>><a href="#" data-pager="<?= $n ?>"><?= $n ?></a></li>
    <?php endforeach ?>
    </ul>
</div>