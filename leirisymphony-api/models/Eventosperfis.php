<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "eventosperfis".
 *
 * @property int $idevento
 * @property int $idperfil
 *
 * @property Eventos $idevento0
 * @property Perfis $idperfil0
 */
class Eventosperfis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventosperfis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idevento', 'idperfil'], 'required'],
            [['idevento', 'idperfil'], 'integer'],
            [['idevento', 'idperfil'], 'unique', 'targetAttribute' => ['idevento', 'idperfil']],
            [['idevento'], 'exist', 'skipOnError' => true, 'targetClass' => Eventos::className(), 'targetAttribute' => ['idevento' => 'id']],
            [['idperfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfis::className(), 'targetAttribute' => ['idperfil' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idevento' => 'Idevento',
            'idperfil' => 'Idperfil',
        ];
    }

    /**
     * Gets query for [[Idevento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdevento0()
    {
        return $this->hasOne(Eventos::className(), ['id' => 'idevento']);
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
}
