<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>
<ul class="sidebar-menu">
<?php foreach ($this->context->items as $item):
    $classes = [];
    $hasChildren = !empty($item['items']);
    $active = $this->context->isActive($item);
    if ($active) $classes[] = 'active';
    if ($hasChildren) $classes[] = 'treeview';
?>
<li<?php if ($classes) echo ' class="',implode(' ', $classes),'"' ?>>
    <a href="<?= Url::to($item['url']) ?>">
        <?php if (!empty($item['i'])) echo '<i class="', $item['i'], '"></i>'; ?>
        <span><?php echo Html::encode($item['label']) ?></span>
        <?php if ($hasChildren): ?><i class="fa fa-angle-left pull-right"></i><?php endif ?>
    </a>
    <?php if ($hasChildren): ?>
    <ul class="treeview-menu">
        <?php foreach ($item['items'] as $child): ?>
        <li <?php if ($this->context->isActive($child)) echo 'class="active"' ?>>
            <a href="<?= Url::to($child['url']) ?>">
                <?php if (!empty($child['i'])) echo '<i class="', $child['i'], '"></i>'; ?>
                <span><?php echo Html::encode($child['label']) ?></span>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
</li>
<?php endforeach ?>
</ul>