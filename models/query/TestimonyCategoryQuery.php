<?php
/**
 * TestimonyCategoryQuery
 *
 * This is the ActiveQuery class for [[\ommu\testimony\models\TestimonyCategory]].
 * @see \ommu\testimony\models\TestimonyCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 May 2018, 01:02 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\testimony\models\query;

class TestimonyCategoryQuery extends \yii\db\ActiveQuery
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
	 */
	public function deleted() 
	{
		return $this->andWhere(['publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\testimony\models\TestimonyCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\testimony\models\TestimonyCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
