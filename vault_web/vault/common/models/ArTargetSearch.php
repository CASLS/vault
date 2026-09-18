<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArTarget;

/**
 * ArTargetSearch represents the model behind the search form of `common\models\ArTarget`.
 */
class ArTargetSearch extends ArTarget
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'media_id', 'overlay_media_id', 'audio_media_id', 'should_auto_close', 'close_after'], 'integer'],
            [['title', 'created_at', 'updated_at'], 'safe'],
            [['physical_width', 'overlay_physical_width'], 'number'],
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
        $query = ArTarget::find();

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
            'task_id' => $this->task_id,
            'media_id' => $this->media_id,
            'physical_width' => $this->physical_width,
            'overlay_media_id' => $this->overlay_media_id,
            'overlay_physical_width' => $this->overlay_physical_width,
            'audio_media_id' => $this->audio_media_id,
            'should_auto_close' => $this->should_auto_close,
            'close_after' => $this->close_after,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
