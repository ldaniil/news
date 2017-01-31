<?php

use yii\db\Migration;
use app\models\User;

class m170125_201702_user_update_email_administrator extends Migration
{
    public function up()
    {
        User::updateAll(['email' => 'administrator@example.com'], ['id' => 1]);
    }

    public function down()
    {
        echo "m170125_201702_user_update_email_administrator cannot be reverted.\n";

        return false;
    }
}
