<?php

use yii\db\Schema;
use yii\db\Migration;

class m150326_105730_add_admin_last_login extends Migration
{
    public function up()
    {
        $this->addColumn('{{%admin}}', 'last_login', 'int(10) UNSIGNED DEFAULT NULL');

    }

    public function down()
    {
        $this->dropColumn('{{%admin}}', 'last_login');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
