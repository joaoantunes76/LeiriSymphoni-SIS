<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "albunsartistas".
 *
 * @property int $idalbum
 * @property int $idartista
 *
 * @property Albuns $idalbum0
 * @property Artistas $idartista0
 */
class Albunsartistas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'albunsartistas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idalbum', 'idartista'], 'required'],
            [['idalbum', 'idartista'], 'integer'],
            [['idalbum', 'idartista'], 'unique', 'targetAttribute' => ['idalbum', 'idartista']],
            [['idalbum'], 'exist', 'skipOnError' => true, 'targetClass' => Albuns::className(), 'targetAttribute' => ['idalbum' => 'id']],
            [['idartista'], 'exist', 'skipOnError' => true, 'targetClass' => Artistas::className(), 'targetAttribute' => ['idartista' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idalbum' => 'Idalbum',
            'idartista' => 'Idartista',
        ];
    }

    /**
     * Gets query for [[Idalbum0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdalbum0()
    {
        return $this->hasOne(Albuns::className(), ['id' => 'idalbum']);
    }

    /**
     * Gets query for [[Idartista0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdartista0()
    {
        return $this->hasOne(Artistas::className(), ['id' => 'idartista']);
    }
}
