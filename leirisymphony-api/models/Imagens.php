<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "imagens".
 *
 * @property int $id
 * @property string $nome
 * @property int|null $idproduto
 *
 * @property Albuns[] $albuns
 * @property Produtos $idproduto0
 */
class Imagens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imagens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
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
            'nome' => 'Nome',
            'idproduto' => 'Idproduto',
        ];
    }

    /**
     * Gets query for [[Albuns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbuns()
    {
        return $this->hasMany(Albuns::className(), ['idimagem' => 'id']);
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
