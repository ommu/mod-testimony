<?php
/**
 * TestimonialsQuery
 *
 * This is the ActiveQuery class for [[\app\modules\testimonial\models\Testimonials]].
 * @see \app\modules\testimonial\models\Testimonials
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 15 May 2018, 01:39 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\testimonial\models\query;

class TestimonialsQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\testimonial\models\Testimonials[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\testimonial\models\Testimonials|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
