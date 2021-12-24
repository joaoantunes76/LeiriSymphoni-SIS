<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "avaliacao".
 *
 * @property int $idproduto
 * @property int $idperfil
 * @property int|null $estrelas
 * @property string|null $comentario
 *
 * @property Perfis $idperfil0
 * @property Produtos $idproduto0
 */
class Avaliacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avaliacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idproduto', 'idperfil'], 'required'],
            [['idproduto', 'idperfil', 'estrelas'], 'integer'],
            [['comentario'], 'string', 'max' => 255],
            [['idproduto', 'idperfil'], 'unique', 'targetAttribute' => ['idproduto', 'idperfil']],
            [['idperfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfis::className(), 'targetAttribute' => ['idperfil' => 'id']],
            [['idproduto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::className(), 'targetAttribute' => ['idproduto' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idproduto' => 'Idproduto',
            'idperfil' => 'Idperfil',
            'estrelas' => 'Estrelas',
            'comentario' => 'Comentario',
        ];
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
     * Gets query for [[Idproduto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdproduto0()
    {
        return $this->hasOne(Produtos::className(), ['id' => 'idproduto']);
    }
}
