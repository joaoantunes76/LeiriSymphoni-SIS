<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Produtos;

/**
 * ProdutosSearch represents the model behind the search form of `common\models\Produtos`.
 */
class ProdutosSearch extends Produtos
{

    public $idsubcategoria0;
    public $idmarca0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idsubcategoria', 'idmarca', 'usado', 'stock'], 'integer'],
            [['nome', 'descricao', 'idsubcategoria0', 'idmarca0'], 'safe'],
            [['preco'], 'number'],
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
        $query = Produtos::find();

        $query->joinWith(['idsubcategoria0', 'idmarca0']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['idsubcategoria0'] = [
            'asc' => ['subcategorias.nome' => SORT_ASC],
            'desc' => ['subcategorias.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['idmarca0'] = [
            'asc' => ['marcas.nome' => SORT_ASC],
            'desc' => ['marcas.nome' => SORT_DESC],
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
            'idsubcategoria' => $this->idsubcategoria,
            'idmarca' => $this->idmarca,
            'usado' => $this->usado,
            'preco' => $this->preco,
            'stock' => $this->stock,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'subcategorias.nome', $this->idsubcategoria0])
            ->andFilterWhere(['like', 'marcas.nome', $this->idmarca0]);

        return $dataProvider;
    }
}
