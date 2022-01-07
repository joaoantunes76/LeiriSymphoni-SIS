<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subcategorias;

/**
 * SubcategoriasSearch represents the model behind the search form of `common\models\Subcategorias`.
 */
class SubcategoriasSearch extends Subcategorias
{
    public $idcategoria0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idcategoria'], 'integer'],
            [['nome', 'idcategoria0'], 'safe'],
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
        $query = Subcategorias::find();

        $query->joinWith(['idcategoria0']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['idcategoria0'] = [
            'asc' => ['categorias.nome' => SORT_ASC],
            'desc' => ['categorias.nome' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'idcategoria' => $this->idcategoria,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
        ->andFilterWhere(['like', 'categorias.nome', $this->idcategoria0]);

        return $dataProvider;
    }
}
