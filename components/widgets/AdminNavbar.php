<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;

class AdminNavbar extends Widget
{
    public $languages = array();
    public $currentLanguage = array();
    public $otherLanguages = array();
    public $user;

    public function init()
    {
        parent::init();
        if (!$this->languages && ($languages = Yii::$app->params['languages'])) {
            $this->languages = $languages;
        }
        $lang = Yii::$app->language;
        if ($this->languages && isset($this->languages[$lang])) {
            $this->currentLanguage = array($lang => $this->languages[$lang]);
            $this->otherLanguages = $this->languages;
            unset($this->otherLanguages[$lang]);
        }
        $this->user = Yii::$app->user;

    }

    public function run()
    {
        return $this->render('admin_navbar', ['user' => $this->user]);
    }
}