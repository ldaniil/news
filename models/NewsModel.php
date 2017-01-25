<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class NewsModel
 * 
 * @property int 	$id
 * @property string $title
 * @property string $content
 * @property string $preview
 * @property int 	$created_at
 * @property int 	$updated_at
 */
class NewsModel extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%news}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'content'], 'required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'title'      => 'Название',
			'content'    => 'Содержание',
			'preview'	 => 'Анонс',
			'created_at' => 'Дата создания'
		];
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	public function getPreview()
	{
		if ($this->isNewRecord) {
			return null;
		}

		$length = mb_strpos($this->content, '</p>') + 4;

		return mb_substr($this->content, 0, $length);
	}
}