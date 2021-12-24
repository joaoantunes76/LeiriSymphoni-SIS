<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subcategorias".
 *
 * @property int $id
 * @property int $idcategoria
 * @property string $nome
 *
 * @property Categorias $idcategoria0
 * @property Produtos[] $produtos
 */
class Subcategorias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subcategorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcategoria', 'nome'], 'required'],
            [['idcategoria'], 'integer'],
            [['nome'], 'string', 'max' => 45],
            [['idcategoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::className(), 'targetAttribute' => ['idcategoria' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idcategoria' => 'Idcategoria',
            'nome' => 'Nome',
        ];
    }

    /**
     * Gets query for [[Idcategoria0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcategoria0()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'idcategoria']);
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produtos::className(), ['idsubcategoria' => 'id']);
    }
}
