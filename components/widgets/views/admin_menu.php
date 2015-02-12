<?php
use yii\helpers\Url;
use yii\helpers\Html;

/*
<li>
    <a href="mailbox-main.html">
        <i class="linecons-mail"></i>
        <span class="title">Mailbox</span>
        <span class="label label-success pull-right">5</span>
    </a>
    <ul>
        <li>
            <a href="mailbox-main.html">
                <span class="title">Inbox</span>
            </a>
        </li>
        <li>
            <a href="mailbox-compose.html">
                <span class="title">Compose Message</span>
            </a>
        </li>
        <li>
            <a href="mailbox-message.html">
                <span class="title">View Message</span>
            </a>
        </li>
    </ul>
</li>
*/
?>
<ul id="main-menu" class="main-menu">
    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
    <?php foreach ($this->context->items as $item):
        $hasChildren = !empty($item['items']);
        $classes = [];
        if ($this->context->isActive($item)):
            $classes[] = 'active';
            if ($hasChildren) $classes[] = 'opened';
        endif;
    ?>
    <li<?php if ($classes) echo ' class="',implode(' ', $classes),'"' ?>>
        <a href="<?= Url::to($item['url']) ?>">
            <?php if (!empty($item['i'])) echo '<i class="', $item['i'], '"></i>'; ?>
            <span class="title"><?php echo Html::encode($item['label']) ?></span>
        </a>
        <?php if ($hasChildren): ?>
        <ul>
            <?php foreach ($item['items'] as $child): ?>
            <li <?php if ($this->context->isActive($child)) echo 'class="active"' ?>>
                <a href="<?= Url::to($child['url']) ?>">
                    <?php if (!empty($child['i'])) echo '<i class="', $child['i'], '"></i>'; ?>
                    <span class="title"><?php echo Html::encode($child['label']) ?></span>
                </a>
            </li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>
    </li>
    <?php endforeach ?>
</ul>
