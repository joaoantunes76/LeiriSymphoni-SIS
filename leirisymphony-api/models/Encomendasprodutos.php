<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encomendasprodutos".
 *
 * @property int $idencomenda
 * @property int $idproduto
 * @property int $quantidade
 *
 * @property Encomendas $idencomenda0
 * @property Produtos $idproduto0
 */
class Encomendasprodutos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomendasprodutos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idencomenda', 'idproduto', 'quantidade'], 'required'],
            [['idencomenda', 'idproduto', 'quantidade'], 'integer'],
            [['idencomenda', 'idproduto'], 'unique', 'targetAttribute' => ['idencomenda', 'idproduto']],
            [['idencomenda'], 'exist', 'skipOnError' => true, 'targetClass' => Encomendas::className(), 'targetAttribute' => ['idencomenda' => 'id']],
            [['idproduto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::className(), 'targetAttribute' => ['idproduto' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idencomenda' => 'Idencomenda',
            'idproduto' => 'Idproduto',
            'quantidade' => 'Quantidade',
        ];
    }

    /**
     * Gets query for [[Idencomenda0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdencomenda0()
    {
        return $this->hasOne(Encomendas::className(), ['id' => 'idencomenda']);
    }

    /**
     * Gets query for [[Idproduto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdproduto0()
    {
        return $this->hasOne(Produtos::className(), ['id' => 'idproduto']);
    }
}
