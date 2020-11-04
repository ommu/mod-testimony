<?php
/**
 * Testimonies
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 May 2018, 01:39 WIB
 * @link https://github.com/ommu/mod-testimony
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
 * @property TestimonyCategory $category
 * @property Users $modified
 *
 */

namespace ommu\testimony\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;
use ommu\member\models\Members;

class Testimonies extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];

	// Variable Search
	public $category_search;
	public $member_search;
	public $userDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_testimonials';
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
			[['testimony_rate'], 'integer', 'max' => TestimonySetting::getInfo('rate_scale')],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestimonyCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'testimonial_id' => Yii::t('app', 'Testimony'),
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
			'userDisplayname' => Yii::t('app', 'User'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
		return $this->hasOne(TestimonyCategory::className(), ['cat_id' => 'cat_id']);
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
	 * @return \ommu\testimony\models\query\TestimoniesQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\testimony\models\query\TestimoniesQuery(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'filter' => TestimonyCategory::getCategory(),
			'value' => function($model, $key, $index, $column) {
				return isset($model->category) ? $model->category->category_name : '-';
			},
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['member_search'] = [
			'attribute' => 'member_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->member) ? $model->member->member_id : '-';
			},
			'visible' => !Yii::$app->request->get('member') ? true : false,
		];
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish, 'Approved,Pending');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['testimonial_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getTestimonies
	 */
	public static function getTestimony($publish=null, $array=true)
	{
		$model = self::find()->alias('t');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }

		$model = $model->orderBy('t.testimonial_id ASC')->all();

        if ($array == true) {
			$items = [];
            if ($model !== null) {
				foreach ($model as $val) {
					$items[$val->testimonial_id] = $val->testimonial_id;
				}
				return $items;
			} else {
                return false;
            }
		} else {
            return $model;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }

            if (isset($this->category) && $this->category->rate_status == 1) {
                $this->addError('testimony_rate', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('testimony_rate')]));
            }
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
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
