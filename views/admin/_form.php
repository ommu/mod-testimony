<?php
/**
 * Testimonies (testimonies)
 * @var $this app\components\View
 * @var $this ommu\testimony\controllers\AdminController
 * @var $model ommu\testimony\models\Testimonies
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 May 2018, 01:46 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\testimony\models\TestimonyCategory;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
$user = isset($model->user) ? $model->user->displayname : '-';
echo $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">'.$user.'</div>'])
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php 
$member = isset($model->member) ? $model->member->member_id : '-';
echo $form->field($model, 'member_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">'.$member.'</div>'])
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('member_id')); ?>

<?php 
$cat_id = TestimonyCategory::getCategory(1);
echo $form->field($model, 'cat_id')
	->dropDownList($cat_id, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php if($model->cat_id == null || (isset($model->category) && $model->category->rate_status == 1)):
echo $form->field($model, 'testimony_rate')
	->textInput(['type' => 'number', 'min' => '1', 'maxlength' => true])
	->label($model->getAttributeLabel('testimony_rate'));
endif; ?>

<?php echo $form->field($model, 'testimony_message')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('testimony_message')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>