<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Albunsartistas;

/**
 * AlbunsartistasSearch represents the model behind the search form of `common\models\Albunsartistas`.
 */
class AlbunsartistasSearch extends Albunsartistas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idalbum', 'idartista'], 'integer'],
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
        $query = Albunsartistas::find();

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
            'idalbum' => $this->idalbum,
            'idartista' => $this->idartista,
        ]);

        return $dataProvider;
    }
}
