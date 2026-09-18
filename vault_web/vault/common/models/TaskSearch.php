<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Task;

/**
 * TaskSearch represents the model behind the search form of `common\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quest_id', 'task_type_id', 'is_active', 'correct_response_image_id', 'incorrect_response_image_id'], 'integer'],
            [['title', 'body', 'answer', 'correct_response_text', 'incorrect_response_text', 'created_at', 'updated_at'], 'safe'],
            [['sort_order'], 'number'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Task::find();

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
            'quest_id' => $this->quest_id,
            'task_type_id' => $this->task_type_id,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'correct_response_image_id' => $this->correct_response_image_id,
            'incorrect_response_image_id' => $this->incorrect_response_image_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'correct_response_text', $this->correct_response_text])
            ->andFilterWhere(['like', 'incorrect_response_text', $this->incorrect_response_text]);

        return $dataProvider;
    }
}
