<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Encomendas;

/**
 * EncomendasSearch represents the model behind the search form of `common\models\Encomendas`.
 */
class EncomendasSearch extends Encomendas
{

    public $idperfil0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idperfil', 'pago'], 'integer'],
            [['estado', 'tipoexpedicao', 'idperfil0'], 'safe'],
            [['preco'], 'number'],
            [['data'], 'date'],
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
        $query = Encomendas::find();

        $query->joinWith(['idperfil0']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['idperfil0'] = [
            'asc' => ['perfis.nome' => SORT_ASC],
            'desc' => ['perfis.nome' => SORT_DESC],
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
            'idperfil' => $this->idperfil,
            'pago' => $this->pago,
            'preco' => $this->preco,
        ]);

        $query->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'tipoexpedicao', $this->tipoexpedicao])
            ->andFilterWhere(['like', 'perfis.nome', $this->idperfil0]);

        return $dataProvider;
    }
}
