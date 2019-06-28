<?php
/**
 * Testimonies (testimonies)
 * @var $this app\components\View
 * @var $this ommu\testimony\controllers\AdminController
 * @var $model ommu\testimony\models\Testimonies
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 May 2018, 01:46 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Testimonies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->testimonial_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->testimonial_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'testimonial_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish, 'Approved,Pending'),
			'format' => 'raw',
		],
		[
			'attribute' => 'category_search',
			'value' => isset($model->category) ? $model->category->title->message : '-',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'member_search',
			'value' => isset($model->member) ? $model->member->member_id : '-',
		],
		[
			'attribute' => 'testimony_rate',
			'value' => $model->testimony_rate ? $model->testimony_rate : '-',
		],
		[
			'attribute' => 'testimony_message',
			'value' => $model->testimony_message ? $model->testimony_message : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
	],
]) ?>