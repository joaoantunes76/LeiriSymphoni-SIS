<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "demonstracoes".
 *
 * @property int $id
 * @property int $idproduto
 * @property string|null $nome
 *
 * @property Produtos $idproduto0
 */
class Demonstracoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'demonstracoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idproduto'], 'required'],
            [['idproduto'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['idproduto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::className(), 'targetAttribute' => ['idproduto' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idproduto' => 'Idproduto',
            'nome' => 'Nome',
        ];
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
