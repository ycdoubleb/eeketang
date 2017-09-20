<?php

namespace common\models\course\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\course\CourseCategory;

/**
 * CourseCategorySearch represents the model behind the search form about `common\models\course\CourseCategory`.
 */
class CourseCategorySearch extends CourseCategory {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'parent_id', 'level', 'sort_order', 'is_show', 'is_hot'], 'integer'],
            [['name', 'mobile_name', 'parent_id_path', 'image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = CourseCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'sort_order' => $this->sort_order,
            'is_show' => $this->is_show,
            'is_hot' => $this->is_hot,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'mobile_name', $this->mobile_name])
                ->andFilterWhere(['like', 'parent_id_path', $this->parent_id_path])
                ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
