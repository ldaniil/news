<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\models\NewsModel;

class NewsSearch extends Model
{
    public function search()
    {
        $query = NewsModel::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => 20
            ])
        ]);
    }
}
