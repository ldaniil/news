<?php

use yii\db\Migration;

class m170127_071921_create_notification extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `notification` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `message` varchar(255) NOT NULL,
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB CHARSET=utf8
        ');
    }

    public function down()
    {
        echo "m170127_071921_create_notification cannot be reverted.\n";

        return false;
    }
}
