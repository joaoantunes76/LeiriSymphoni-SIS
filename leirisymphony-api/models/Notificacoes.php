<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notificacoes".
 *
 * @property int $id
 * @property string $mensagem
 * @property string $datatime
 * @property string $expiracao
 * @property int $idperfil
 * @property int $idproduto
 *
 * @property Produtosfavoritos $idperfil0
 */
class Notificacoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mensagem', 'datatime', 'expiracao', 'idperfil', 'idproduto'], 'required'],
            [['mensagem'], 'string'],
            [['datatime', 'expiracao'], 'safe'],
            [['idperfil', 'idproduto'], 'integer'],
            [['idperfil', 'idproduto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtosfavoritos::className(), 'targetAttribute' => ['idperfil' => 'idperfil', 'idproduto' => 'idproduto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mensagem' => 'Mensagem',
            'datatime' => 'Datatime',
            'expiracao' => 'Expiracao',
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
        return $this->hasOne(Produtosfavoritos::className(), ['idperfil' => 'idperfil', 'idproduto' => 'idproduto']);
    }
}
