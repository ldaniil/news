<?php

use yii\db\Migration;

class m170127_070831_create_notification_setting extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE notification_setting (
              id int(11) NOT NULL AUTO_INCREMENT,
              model varchar(255) NOT NULL,
              event varchar(255) NOT NULL,
              title varchar(255) NOT NULL,
              message varchar(255) NOT NULL,
              routes varchar(255) NOT NULL,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB CHARSET=utf8
        ');

        $this->batchInsert(
            'notification_setting',
            [
                'model',
                'event',
                'title',
                'message',
                'routes'
            ],
            [
                [
                    'app\models\RegistrationForm',
                    'new',
                    'Зарегистрирован новый пользователь',
                    '<p>Зарегистрирован новый пользователь</p>
                     <p>Имя: <strong>user.name</strong></p>
                     <p>Email: <strong>user.email</strong></p>',
                    '{"administrator":{"enable":"1","exclude":[],"transports":["email"],"role":"administrator"},"user":{"exclude":[],"role":"user","enable":0,"transports":[]}}',
                ],
                [
                    'app\models\NewsModel',
                    'new',
                    'Новая новость news.title',
                    '<p>Здравствуйте recipient.name, добавлена новая новость news.link</p>',
                    '{"administrator":{"exclude":[],"role":"administrator","enable":0,"transports":[]},"user":{"enable":"1","exclude":[],"transports":["browser"],"role":"user"}}',
                ],
            ]
        );
    }

    public function down()
    {
        echo "m170127_070831_create_notification_setting cannot be reverted.\n";

        return false;
    }
}
