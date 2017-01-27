<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;
use app\entities\notification\SenderInterface as NotificationSenderInterface;
use app\entities\Notification;

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
class NewsModel extends ActiveRecord implements NotificationSenderInterface
{
	const EVENT_NEW = 'new';

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

	/**
	 * @param bool  $insert
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		if ($insert) {
			$this->trigger(self::EVENT_NEW);
		}

		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * Возвращает анонс новости
	 *
	 * @return null|string
	 */
	public function getPreview()
	{
		if ($this->isNewRecord) {
			return null;
		}

		$length = mb_strpos($this->content, '</p>') + 4;

		return mb_substr($this->content, 0, $length);
	}

	/**
	 * Возвращает url
	 *
	 * @return string
	 */
	public function getUrl()
	{ 
		return Url::home(true) . 'view/' . $this->id;
	}

	/**
	 * Возвращает ссылку
	 */
	public function getLink()
	{
		return Html::a($this->title, $this->url);
	}

	/**
	 * @inheritdoc
	 */
	public function getNotificationTemplateVariables()
	{
		return [
			'news.title' => $this->title,
			'news.link'   => $this->link
		];
	}
}