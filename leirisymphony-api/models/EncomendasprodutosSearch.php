<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Encomendasprodutos;

/**
 * EncomendasprodutosSearch represents the model behind the search form of `common\models\Encomendasprodutos`.
 */
class EncomendasprodutosSearch extends Encomendasprodutos
{

    public $idproduto0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idencomenda', 'idproduto', 'quantidade'], 'integer'],
            ['idproduto0', 'safe']
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
        $query = Encomendasprodutos::find();

        $query->joinWith(['idproduto0']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['idproduto0'] = [
            'asc' => ['produtos.nome' => SORT_ASC],
            'desc' => ['produtos.nome' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['idencomenda' => $this->idencomenda, 'idproduto' => $this->idproduto, 'quantidade' => $this->quantidade,])
            ->andFilterWhere(['like', 'produtos.nome', $this->idproduto0]);;

        return $dataProvider;
    }
}
