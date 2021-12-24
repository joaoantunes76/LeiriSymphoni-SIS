<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encomendas".
 *
 * @property int $id
 * @property int $idperfil
 * @property string $estado
 * @property int $pago
 * @property float $preco
 * @property string $tipoexpedicao
 *
 * @property Encomendasprodutos[] $encomendasprodutos
 * @property Perfis $idperfil0
 * @property Produtos[] $idprodutos
 */
class Encomendas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomendas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idperfil', 'estado', 'pago', 'preco', 'tipoexpedicao'], 'required'],
            [['idperfil', 'pago'], 'integer'],
            [['estado', 'tipoexpedicao'], 'string'],
            [['preco'], 'number'],
            [['idperfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfis::className(), 'targetAttribute' => ['idperfil' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idperfil' => 'Idperfil',
            'estado' => 'Estado',
            'pago' => 'Pago',
            'preco' => 'Preco',
            'tipoexpedicao' => 'Tipoexpedicao',
        ];
    }

    /**
     * Gets query for [[Encomendasprodutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendasprodutos()
    {
        return $this->hasMany(Encomendasprodutos::className(), ['idencomenda' => 'id']);
    }

    /**
     * Gets query for [[Idperfil0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfil0()
    {
        return $this->hasOne(Perfis::className(), ['id' => 'idperfil']);
    }

    /**
     * Gets query for [[Idprodutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdprodutos()
    {
        return $this->hasMany(Produtos::className(), ['id' => 'idproduto'])->viaTable('encomendasprodutos', ['idencomenda' => 'id']);
    }
}
