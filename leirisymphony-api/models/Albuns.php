<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "albuns".
 *
 * @property int $id
 * @property string $nome
 * @property float $preco
 * @property string $datalancamento
 * @property int $idimagem
 *
 * @property Albunsartistas[] $albunsartistas
 * @property Artistas[] $idartistas
 * @property Imagens $idimagem0
 * @property Musicas[] $musicas
 */
class Albuns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'albuns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'preco', 'datalancamento', 'idimagem'], 'required'],
            [['preco'], 'number'],
            [['datalancamento'], 'safe'],
            [['idimagem'], 'integer'],
            [['nome'], 'string', 'max' => 45],
            [['idimagem'], 'exist', 'skipOnError' => true, 'targetClass' => Imagens::className(), 'targetAttribute' => ['idimagem' => 'id']],
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
            'preco' => 'Preco',
            'datalancamento' => 'Datalancamento',
            'idimagem' => 'Idimagem',
        ];
    }

    /**
     * Gets query for [[Albunsartistas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbunsartistas()
    {
        return $this->hasMany(Albunsartistas::className(), ['idalbum' => 'id']);
    }

    /**
     * Gets query for [[Idartistas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdartistas()
    {
        return $this->hasMany(Artistas::className(), ['id' => 'idartista'])->viaTable('albunsartistas', ['idalbum' => 'id']);
    }

    /**
     * Gets query for [[Idimagem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdimagem0()
    {
        return $this->hasOne(Imagens::className(), ['id' => 'idimagem']);
    }

    /**
     * Gets query for [[Musicas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMusicas()
    {
        return $this->hasMany(Musicas::className(), ['idalbuns' => 'id']);
    }
}
