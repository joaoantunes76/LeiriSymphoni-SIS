<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produtosfavoritos".
 *
 * @property int $idperfil
 * @property int $idproduto
 *
 * @property Perfis $idperfil0
 * @property Produtos $idproduto0
 * @property Notificacoes[] $notificacoes
 */
class Produtosfavoritos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtosfavoritos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idperfil', 'idproduto'], 'required'],
            [['idperfil', 'idproduto'], 'integer'],
            [['idperfil', 'idproduto'], 'unique', 'targetAttribute' => ['idperfil', 'idproduto']],
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
            'idperfil' => 'Idperfil',
            'idproduto' => 'Idproduto',
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

    /**
     * Gets query for [[Notificacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificacoes()
    {
        return $this->hasMany(Notificacoes::className(), ['idperfil' => 'idperfil', 'idproduto' => 'idproduto']);
    }
}
