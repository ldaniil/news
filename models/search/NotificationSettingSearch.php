<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NotificationSettingModel;

class NotificationSettingSearch extends Model
{
    public function search()
    {
        $query = NotificationSettingModel::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }
}
