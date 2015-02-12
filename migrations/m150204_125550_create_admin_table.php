<?php

use yii\db\Migration;

class m150204_125550_create_admin_table extends Migration
{
     public function up()
    {
        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%admin}}', [
            'id' => "int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
            'username' => "varchar(60) NOT NULL",
            'auth_key' => 'varchar(32) NOT NULL',
            'pwd' => 'varchar(128) DEFAULT NULL',
            'pwd_reset_token' => 'varchar(128) DEFAULT NULL',
            'email' => 'varchar(128) DEFAULT NULL',
            'created_at' => "int(10) UNSIGNED NOT NULL",
            'updated_at' => "int(10) UNSIGNED NOT NULL",
        ], $tableOptions);

        $this->createIndex('username', '{{%admin}}', 'username', true);
        $this->createIndex('email', '{{%admin}}', 'email', true);

        $this->insert('{{%admin}}', [
            'username' => 'admin',
            'pwd' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'email' => 'admin@example.org',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down() {
        $this->dropTable('{{%admin}}');
    }

}