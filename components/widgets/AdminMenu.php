<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;

class AdminMenu extends Widget
{
    public $items = [];

    public $route;

    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }

        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($this->items[$i]);
                continue;
            }
            if (isset($item['items'])) {
                foreach ($item['items'] as $j => $subitem) {
                    if (isset($subitem['visible']) && !$subitem['visible']) {
                        unset($this->items[$i]['items'][$j]);
                        continue;
                    }
                }
            }
        }
    }

    public function run()
    {
        return $this->render('admin_menu');
    }

    public function isActive(&$item) {
        if (isset($item['active']) && $item['active']) return true;
        if (!empty($item['items'])) {
            foreach ($item['items'] as $child) {
                if ($this->isActive($child)) return true;
            }
        }
        if (empty($item['match']) || !is_array($item['url'])) {
            $item['active'] = false;
            return false;
        }
        if ($item['match'] == 'action' && $this->route == $item['url'][0]) {
            $item['active'] = true;
            return true;
        } elseif ($item['match'] == 'controller') {
            $a = explode('/', $item['url'][0]);
            $b = explode('/', $this->route);
            if ($a[0] == $b[0]) {
                $item['active'] = true;
                return true;
            }
        }
        return false;
    }
}