<?php

namespace common\models\course\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\course\CourseAttribute;

/**
 * CourseAttributeSearch represents the model behind the search form about `common\models\course\CourseAttribute`.
 */
class CourseAttributeSearch extends CourseAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_model_id', 'type', 'input_type', 'sort_order', 'index_type'], 'integer'],
            [['name', 'values'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = CourseAttribute::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'course_model_id' => $this->course_model_id,
            'type' => $this->type,
            'input_type' => $this->input_type,
            'sort_order' => $this->sort_order,
            'index_type' => $this->index_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'values', $this->values]);
        
        $query->orderBy('course_model_id,sort_order');

        return $dataProvider;
    }
}
