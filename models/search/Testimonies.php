<?php
/**
 * Testimonies
 *
 * Testimonies represents the model behind the search form about `ommu\testimony\models\Testimonies`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 May 2018, 01:46 WIB
 * @link https://github.com/ommu/mod-testimony
 *
 */

namespace ommu\testimony\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\testimony\models\Testimonies as TestimoniesModel;

class Testimonies extends TestimoniesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['testimonial_id', 'publish', 'cat_id', 'user_id', 'member_id', 'testimony_rate', 'modified_id'], 'integer'],
			[['testimony_message', 'creation_date', 'modified_date', 'updated_date',
				'category_search', 'member_search', 'userDisplayname', 'modifiedDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = TestimoniesModel::find()->alias('t');
		$query->joinWith([
			'category.title category', 
			'member member', 
			'user user', 
			'modified modified'
		]);

		$query->groupBy(['testimonial_id']);

        // add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['member_search'] = [
			'asc' => ['member.member_id' => SORT_ASC],
			'desc' => ['member.member_id' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['testimonial_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.testimonial_id' => $this->testimonial_id,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.member_id' => isset($params['member']) ? $params['member'] : $this->member_id,
			't.testimony_rate' => $this->testimony_rate,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

		$query->andFilterWhere(['like', 't.testimony_message', $this->testimony_message])
			->andFilterWhere(['like', 'category.message', $this->category_search])
			->andFilterWhere(['like', 'member.member_id', $this->member_search])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
