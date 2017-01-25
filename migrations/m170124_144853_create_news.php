<?php

use yii\db\Migration;

class m170124_144853_create_news extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE news (
              id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              title VARCHAR(255) NOT NULL,
              content TEXT NOT NULL,
              created_at INT(11) NOT NULL,
              updated_at INT(11) NOT NULL,
              PRIMARY KEY (id)
            ) ENGINE = INNODB CHARACTER SET utf8
        ');
    }

    public function down()
    {
        echo "m170124_144853_create_news cannot be reverted.\n";

        return false;
    }
}
