<?php
/**
 * TestimoniesQuery
 *
 * This is the ActiveQuery class for [[\ommu\testimony\models\Testimonies]].
 * @see \ommu\testimony\models\Testimonies
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 May 2018, 01:39 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\testimony\models\query;

class TestimoniesQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\testimony\models\Testimonies[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\testimony\models\Testimonies|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
