<?php
/**
 * Testimony Settings (testimony-setting)
 * @var $this app\components\View
 * @var $this ommu\testimony\controllers\SettingController
 * @var $model ommu\testimony\models\TestimonySetting
 * @var $form app\components\widgets\ActiveForm
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 May 2018, 00:58 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\testimony\models\TestimonySetting;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
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
if ($model->isNewRecord && !$model->getErrors()) {
	$model->license = $model->licenseCode();
}
echo $form->field($model, 'license')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
    ->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php echo $form->field($model, 'rate_scale')
	->textInput(['type' => 'number', 'min' => '1', 'maxlength' => true])
	->label($model->getAttributeLabel('rate_scale')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>