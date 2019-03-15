<?php
/**
 * testimony module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 14 May 2018, 18:12 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

namespace ommu\testimony;

use Yii;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'ommu\testimony\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLayoutPath()
	{
		if(Yii::$app->view->theme)
			return Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'layouts';
		else
			return parent::getLayoutPath();
	}
}
