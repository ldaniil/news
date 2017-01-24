<?php

use yii\db\Migration;

use app\models\User;

class m170123_161407_create_user extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE user(
              id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              email VARCHAR(255) NOT NULL,
              password CHAR(32) NOT NULL,
              status SMALLINT(6) NOT NULL DEFAULT 1,
              token CHAR(32) DEFAULT NULL,
              fio VARCHAR(255) NOT NULL,
              created_at INT(11) NOT NULL,
              updated_at INT(11) NOT NULL,
              PRIMARY KEY (id),
              UNIQUE INDEX email USING BTREE (email),
              UNIQUE INDEX token USING BTREE (token)
            ) ENGINE = INNODB CHARACTER SET utf8
        ');

        $user = new User([
            'email'    => 'administrator',
            'password' => User::generatePasswordHash('password'),
            'status'   => User::STATUS_ACTIVE,
            'fio'      => 'Администратор',
        ]);

        $user->save();
        $user->setRole(User::ROLE_ADMINISTRATOR);
    }

    public function down()
    {
        echo "m170123_161407_create_user cannot be reverted.\n";

        return false;
    }
}
