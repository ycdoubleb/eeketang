<?php

namespace common\models\course\searchs;

use common\models\course\Course;
use common\models\course\CourseCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CourseSearch represents the model behind the search form about `common\models\course\Course`.
 */
class CourseSearch extends Course
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cat_id', 'type', 'teacher_id', 'is_recommend', 'is_publish', 'zan_count', 'favorites_count', 'comment_count', 'publish_time', 'publisher', 'create_by', 'created_at', 'updated_at', 'course_model_id'], 'integer'],
            [['courseware_name','template_sn', 'img', 'path', 'learning_objectives', 'introduction', 'content', 'keywords','courseware_sn'], 'safe'],
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
        $query = Course::find();

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
        $catids = CourseCategory::getCatChildrenIds($this->cat_id, true);
        if(empty($catids)){
            $catids = $this->cat_id;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cat_id' => $catids,
            'type' => $this->type,
            'template_sn' => $this->template_sn,
            'courseware_sn' => $this->courseware_sn,
            'teacher_id' => $this->teacher_id,
            'is_recommend' => $this->is_recommend,
            'is_publish' => $this->is_publish,
            'course_order' => $this->course_order,
            'sort_order' => $this->sort_order,
            'play_count' => $this->play_count,
            'zan_count' => $this->zan_count,
            'favorites_count' => $this->favorites_count,
            'comment_count' => $this->comment_count,
            'publish_time' => $this->publish_time,
            'publisher' => $this->publisher,
            'course_model_id' => $this->course_model_id,
        ]);

        $query->andFilterWhere(['like', 'courseware_name', $this->courseware_name])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'learning_objectives', $this->learning_objectives])
            ->andFilterWhere(['like', 'introduction', $this->introduction])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'keywords', $this->keywords]);

        return $dataProvider;
    }
}
