<?php
/**
 * testimony module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
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
}
