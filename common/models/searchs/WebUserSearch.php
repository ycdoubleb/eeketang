<?php

namespace common\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WebUser;

/**
 * WebUserSearch represents the model behind the search form about `common\models\WebUser`.
 */
class WebUserSearch extends WebUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username', 'password', 'real_name', 'tel', 'school_id', 'organization', 'create_time', 'end_time', 'avatar', 'name', 'remarks', 'edu_id', 'workgroup_id', 'workgroup_name', 'workgroup_code', 'access_token', 'last_login_time', 'auth_key'], 'safe'],
            [['sex', 'subjects', 'source', 'status', 'role', 'usages', 'account_non_locked', 'max_user', 'purchase'], 'integer'],
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
        $query = WebUser::find();

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
            'sex' => $this->sex,
            'subjects' => $this->subjects,
            'source' => $this->source,
            'create_time' => $this->create_time,
            'status' => $this->status,
            'role' => $this->role,
            'usages' => $this->usages,
            'account_non_locked' => $this->account_non_locked,
            'max_user' => $this->max_user,
            'purchase' => $this->purchase,
            'last_login_time' => $this->last_login_time,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'school_id', $this->school_id])
            ->andFilterWhere(['like', 'organization', $this->organization])
            ->andFilterWhere(['like', 'end_time', $this->end_time])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'edu_id', $this->edu_id])
            ->andFilterWhere(['like', 'workgroup_id', $this->workgroup_id])
            ->andFilterWhere(['like', 'workgroup_name', $this->workgroup_name])
            ->andFilterWhere(['like', 'workgroup_code', $this->workgroup_code])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        return $dataProvider;
    }
}
