<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pedidosdecontacto".
 *
 * @property int $idproblema
 * @property int $idperfil
 * @property string $mensagem
 * @property string $email
 *
 * @property Perfis $idperfil0
 * @property Tipoinformacoes $idproblema0
 */
class Pedidosdecontacto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedidosdecontacto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idproblema', 'idperfil', 'mensagem', 'email'], 'required'],
            [['idproblema', 'idperfil'], 'integer'],
            [['mensagem'], 'string'],
            [['email'], 'string', 'max' => 64],
            [['idproblema', 'idperfil'], 'unique', 'targetAttribute' => ['idproblema', 'idperfil']],
            [['idperfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfis::className(), 'targetAttribute' => ['idperfil' => 'id']],
            [['idproblema'], 'exist', 'skipOnError' => true, 'targetClass' => Tipoinformacoes::className(), 'targetAttribute' => ['idproblema' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idproblema' => 'Idproblema',
            'idperfil' => 'Idperfil',
            'mensagem' => 'Mensagem',
            'email' => 'Email',
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
     * Gets query for [[Idproblema0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdproblema0()
    {
        return $this->hasOne(Tipoinformacoes::className(), ['id' => 'idproblema']);
    }
}
