<?php
/**
 * TestimonySetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 May 2018, 00:58 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 * This is the model class for table "ommu_testimonial_setting".
 *
 * The followings are the available columns in table "ommu_testimonial_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $rate_scale
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\testimony\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class TestimonySetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_testimonial_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_keyword', 'meta_description', 'rate_scale'], 'required'],
			[['permission', 'rate_scale', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description'], 'string'],
			[['modified_date'], 'safe'],
			[['rate_scale'], 'integer', 'max' => 10],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'rate_scale' => Yii::t('app', 'Rate Scale'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['rate_scale'] = [
			'attribute' => 'rate_scale',
			'value' => function($model, $key, $index, $column) {
				return $model->rate_scale;
			},
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
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->permission);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => 1])->one();
            return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$moduleName = "module name";
		$module = strtolower(Yii::$app->controller->module->id);
        if (($module = Yii::$app->moduleManager->getModule($module)) != null) {
            $moduleName = strtolower($module->getName());
        }

		$items = array(
			1 => Yii::t('app', 'Yes, the public can view {module} unless they are made private.', ['module' => $moduleName]),
			0 => Yii::t('app', 'No, the public cannot view {module}.', ['module' => $moduleName]),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if (!$this->isNewRecord) {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
