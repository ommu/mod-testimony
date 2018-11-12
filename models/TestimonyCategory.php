<?php
/**
 * TestimonyCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 May 2018, 01:02 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_testimonial_category".
 *
 * The followings are the available columns in table "ommu_testimonial_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $rate_status
 * @property integer $category_name
 * @property integer $category_desc
 * @property string $profile_alow
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Testimonies[] $testimonies
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\testimony\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\SourceMessage;
use ommu\users\models\Users;

class TestimonyCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['profile_alow','modified_date','modified_search','updated_date','category_desc_i'];
	public $category_name_i;
	public $category_desc_i;

	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_testimonial_category';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['category_name_i', 'category_desc_i'], 'required'],
			[['publish', 'rate_status', 'category_name', 'category_desc', 'creation_id', 'modified_id'], 'integer'],
			[['category_name_i', 'category_desc_i'], 'string'],
			//[['profile_alow'], 'serialize'],
			[['profile_alow', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['category_name_i'], 'string', 'max' => 64],
			[['category_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'rate_status' => Yii::t('app', 'Rate'),
			'category_name' => Yii::t('app', 'Category'),
			'category_desc' => Yii::t('app', 'Description'),
			'profile_alow' => Yii::t('app', 'Profile Alow'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_name_i' => Yii::t('app', 'Category'),
			'category_desc_i' => Yii::t('app', 'Description'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTestimonies()
	{
		return $this->hasMany(Testimonies::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'category_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'category_desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\testimony\models\query\TestimonyCategoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\testimony\models\query\TestimonyCategoryQuery(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['category_name_i'] = [
			'attribute' => 'category_name_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->title) ? $model->title->message : '-';
			},
		];
		$this->templateColumns['category_desc_i'] = [
			'attribute' => 'category_desc_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->description) ? $model->description->message : '-';
			},
		];
		$this->templateColumns['profile_alow'] = [
			'attribute' => 'profile_alow',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->profile_alow);
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter' => Html::input('date', 'creation_date', Yii::$app->request->get('creation_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['rate_status'] = [
			'attribute' => 'rate_status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['category/rate', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->rate_status, 'Enable,Disable');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['category/publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['cat_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.category_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true) {
			$items = [];
			if($model !== null) {
				foreach($model as $val) {
					$items[$val->cat_id] = $val->title->message;
				}
				return $items;
			} else
				return false;
		} else 
			return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		$this->category_name_i = isset($this->title) ? $this->title->message : '';
		$this->category_desc_i = isset($this->description) ? $this->description->message : '';
		$this->profile_alow = unserialize($this->profile_alow);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = $this->urlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->category_name)) {
				$category_name = new SourceMessage();
				$category_name->location = $location.'_title';
				$category_name->message = $this->category_name_i;
				if($category_name->save())
					$this->category_name = $category_name->id;
				
			} else {
				$category_name = SourceMessage::findOne($this->category_name);
				$category_name->message = $this->category_name_i;
				$category_name->save();
			}

			if($insert || (!$insert && !$this->category_desc)) {
				$category_desc = new SourceMessage();
				$category_desc->location = $location.'_description';
				$category_desc->message = $this->category_desc_i;
				if($category_desc->save())
					$this->category_desc = $category_desc->id;
				
			} else {
				$category_desc = SourceMessage::findOne($this->category_desc);
				$category_desc->message = $this->category_desc_i;
				$category_desc->save();
			}

			$this->profile_alow = serialize($this->profile_alow);
		}
		return true;
	}
}
