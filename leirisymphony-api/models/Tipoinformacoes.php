<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tipoinformacoes".
 *
 * @property int $id
 * @property string $nome
 * @property string $tipo
 *
 * @property Perfis[] $idperfils
 * @property Pedidosdecontacto[] $pedidosdecontactos
 */
class Tipoinformacoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipoinformacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'tipo'], 'required'],
            [['tipo'], 'string'],
            [['nome'], 'string', 'max' => 45],
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
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Idperfils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfils()
    {
        return $this->hasMany(Perfis::className(), ['id' => 'idperfil'])->viaTable('pedidosdecontacto', ['idproblema' => 'id']);
    }

    /**
     * Gets query for [[Pedidosdecontactos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidosdecontactos()
    {
        return $this->hasMany(Pedidosdecontacto::className(), ['idproblema' => 'id']);
    }
}
