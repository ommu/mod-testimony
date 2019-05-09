<?php
/**
 * Testimonies (testimonies)
 * @var $this yii\web\View
 * @var $this ommu\testimony\controllers\AdminController
 * @var $model ommu\testimony\models\Testimonies
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 May 2018, 01:46 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\testimony\models\TestimonyCategory;
?>

<?php $form = ActiveForm::begin([
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
$user = isset($model->user) ? $model->user->displayname : '-';
echo $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">'.$user.'</div>'])
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id'), ['class'=>'control-label col-sm-3 col-xs-12']); ?>

<?php 
$member = isset($model->member) ? $model->member->member_id : '-';
echo $form->field($model, 'member_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">'.$member.'</div>'])
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('member_id'), ['class'=>'control-label col-sm-3 col-xs-12']); ?>

<?php 
$cat_id = TestimonyCategory::getCategory(1);
echo $form->field($model, 'cat_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($cat_id, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-sm-3 col-xs-12']); ?>

<?php if($model->cat_id == null || (isset($model->category) && $model->category->rate_status == 1)):
echo $form->field($model, 'testimony_rate', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'number', 'min' => '1', 'maxlength' => true])
	->label($model->getAttributeLabel('testimony_rate'), ['class'=>'control-label col-sm-3 col-xs-12']);
endif; ?>

<?php echo $form->field($model, 'testimony_message', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('testimony_message'), ['class'=>'control-label col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>