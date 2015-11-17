<?php

use yii\db\Schema;
use yii\db\Migration;

class m150218_171832_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%user}}', [
            'id' => "int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
            'username' => "varchar(128) NOT NULL",
            'auth_key' => 'varchar(32) NOT NULL',
            'pwd' => 'varchar(128) DEFAULT NULL',
            'pwd_reset_token' => 'varchar(128) DEFAULT NULL',
            'email' => 'varchar(128) DEFAULT NULL',
            'avatar' => 'varchar(128) DEFAULT NULL',
            'created_at' => "int(10) UNSIGNED NOT NULL",
            'updated_at' => "int(10) UNSIGNED NOT NULL",
        ], $tableOptions);

        $this->createIndex('username', '{{%user}}', 'username', true);
        $this->createIndex('email', '{{%user}}', 'email', true);

        $this->insert('{{%user}}', [
            'username' => 'demo',
            'pwd' => Yii::$app->security->generatePasswordHash('demo'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'email' => 'user@example.org',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
