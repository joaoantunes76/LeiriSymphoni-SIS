<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "artistas".
 *
 * @property int $id
 * @property string $nome
 *
 * @property Albunsartistas[] $albunsartistas
 * @property Albuns[] $idalbums
 */
class Artistas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'artistas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[Albunsartistas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbunsartistas()
    {
        return $this->hasMany(Albunsartistas::className(), ['idartista' => 'id']);
    }

    /**
     * Gets query for [[Idalbums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdalbums()
    {
        return $this->hasMany(Albuns::className(), ['id' => 'idalbum'])->viaTable('albunsartistas', ['idartista' => 'id']);
    }
}
