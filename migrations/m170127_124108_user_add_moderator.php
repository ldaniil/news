<?php

use yii\db\Migration;
use app\models\User;

class m170127_124108_user_add_moderator extends Migration
{
    public function up()
    {
        $user = new User([
            'email'    => 'moderator@example.com',
            'password' => User::generatePasswordHash('password'),
            'status'   => User::STATUS_ACTIVE,
            'fio'      => 'Модератор',
        ]);

        $user->save();
        $user->setRole(User::ROLE_MODERATOR);
    }

    public function down()
    {
        echo "m170127_124108_add cannot be reverted.\n";

        return false;
    }
}
