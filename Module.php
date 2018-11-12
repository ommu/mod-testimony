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

		// custom initialization code goes here
	}
}
