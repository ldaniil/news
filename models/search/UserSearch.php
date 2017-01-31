<?php

namespace app\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\models\User;

/**
 * Class UserSearch
 *
 * @package app\models\search
 */
class UserSearch extends Model
{
    /**
     * Поиск пользователей
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = User::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => 10,
            ])
        ]);
    }
}
