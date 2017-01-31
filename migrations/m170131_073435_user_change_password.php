<?php

use yii\db\Migration;

class m170131_073435_user_change_password extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'password', 'CHAR(32) DEFAULT NULL');
    }

    public function down()
    {
        echo "m170131_073435_user_change_password cannot be reverted.\n";

        return false;
    }
}
