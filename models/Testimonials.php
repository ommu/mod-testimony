<?php
/**
 * Testimonials
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 15 May 2018, 01:39 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_testimonials".
 *
 * The followings are the available columns in table "ommu_testimonials":
 * @property integer $testimonial_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $user_id
 * @property integer $member_id
 * @property integer $testimony_rate
 * @property string $testimony_message
 * @property string $creation_date
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Members $member
 * @property TestimonialCategory $category
 * @property Users $modified
 *
 */

namespace app\modules\testimonial\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\user\models\Users;
use app\modules\member\models\Members;

class Testimonials extends \app\components\ActiveRecord
{
	use \ommu\traits\GridViewTrait;

	public $gridForbiddenColumn = ['modified_date','modified_search','updated_date'];

	// Variable Search
	public $category_search;
	public $member_search;
	public $user_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_testimonials';
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
			[['testimony_message'], 'required'],
			[['publish', 'cat_id', 'user_id', 'member_id', 'testimony_rate', 'modified_id'], 'integer'],
			[['testimony_message'], 'string'],
			[['cat_id', 'testimony_rate', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['testimony_rate'], 'integer', 'max' => TestimonialSetting::getInfo('rate_scale')],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestimonialCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'testimonial_id' => Yii::t('app', 'Testimonial'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'user_id' => Yii::t('app', 'User'),
			'member_id' => Yii::t('app', 'Member'),
			'testimony_rate' => Yii::t('app', 'Rate'),
			'testimony_message' => Yii::t('app', 'Message'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_search' => Yii::t('app', 'Category'),
			'member_search' => Yii::t('app', 'Member'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMember()
	{
		return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(TestimonialCategory::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\testimonial\models\query\TestimonialsQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\testimonial\models\query\TestimonialsQuery(get_called_class());
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'filter' => TestimonialCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->category_name : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		if(!Yii::$app->request->get('member')) {
			$this->templateColumns['member_search'] = [
				'attribute' => 'member_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->member) ? $model->member->member_id : '-';
				},
			];
		}
		$this->templateColumns['testimony_rate'] = [
			'attribute' => 'testimony_rate',
			'value' => function($model, $key, $index, $column) {
				return isset($model->testimony_rate) ? $model->testimony_rate : '-';
			},
		];
		$this->templateColumns['testimony_message'] = [
			'attribute' => 'testimony_message',
			'value' => function($model, $key, $index, $column) {
				return $model->testimony_message;
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
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, 'Approved,Pending');
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
				->where(['testimonial_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getTestimonials
	 */
	public static function getTestimonial($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('t.testimonial_id ASC')->all();

		if($array == true) {
			$items = [];
			if($model !== null) {
				foreach($model as $val) {
					$items[$val->testimonial_id] = $val->testimonial_id;
				}
				return $items;
			} else
				return false;
		} else 
			return $model;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			if(isset($this->category) && $this->category->rate_status == 1)
				$this->addError('testimony_rate', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('testimony_rate')]));
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert) {
			/*
			Yii::$app->mailer->compose()
				->setFrom('emailasale@gmail.com')
				->setTo($model->user->email)
				->setSubject(Yii::t('app', ''))
				->setTextBody(Yii::t('app', 'Plain text content'))
				->setHtmlBody('')
				->send();
			*/
		}
	}
}
