<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "musicas".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $ficheiro
 * @property int $idalbuns
 *
 * @property Albuns $idalbuns0
 */
class Musicas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'musicas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'idalbuns'], 'required'],
            [['idalbuns'], 'integer'],
            [['nome'], 'string', 'max' => 45],
            [['ficheiro'], 'string', 'max' => 255],
            [['idalbuns'], 'exist', 'skipOnError' => true, 'targetClass' => Albuns::className(), 'targetAttribute' => ['idalbuns' => 'id']],
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
            'ficheiro' => 'Ficheiro',
            'idalbuns' => 'Idalbuns',
        ];
    }

    /**
     * Gets query for [[Idalbuns0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdalbuns0()
    {
        return $this->hasOne(Albuns::className(), ['id' => 'idalbuns']);
    }
}
