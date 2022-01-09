<?php

namespace app\models;

use Bluerhinos\phpMQTT;
use Yii;

/**
 * This is the model class for table "encomendas".
 *
 * @property int $id
 * @property int $idperfil
 * @property string $estado
 * @property int $pago
 * @property float $preco
 * @property string $tipoexpedicao
 * @property string $data
 *
 * @property Encomendasprodutos[] $encomendasprodutos
 * @property Perfis $idperfil0
 * @property Produtos[] $idprodutos
 */
class Encomendas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomendas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idperfil', 'estado', 'pago', 'preco', 'tipoexpedicao'], 'required'],
            [['idperfil', 'pago'], 'integer'],
            [['estado', 'tipoexpedicao'], 'string'],
            [['preco'], 'number'],
            [['data'], 'date', 'format' => 'Y-m-d'],
            [['idperfil'], 'exist', 'skipOnError' => true, 'targetClass' => Perfis::className(), 'targetAttribute' => ['idperfil' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idperfil' => 'Idperfil',
            'estado' => 'Estado',
            'pago' => 'Pago',
            'preco' => 'Preco',
            'tipoexpedicao' => 'Tipoexpedicao',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[Encomendasprodutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendasprodutos()
    {
        return $this->hasMany(Encomendasprodutos::className(), ['idencomenda' => 'id']);
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

    /**
     * Gets query for [[Idprodutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdprodutos()
    {
        return $this->hasMany(Produtos::className(), ['id' => 'idproduto'])->viaTable('encomendasprodutos', ['idencomenda' => 'id']);
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $id = $this->id;
        $myObj = new \stdClass();
        $myObj->estado = $this-> estado;
        $myJSON = json_encode($myObj->estado);
        if (!$insert && isset($changedAttributes['estado'])) {
            $this->FazPublish("encomenda".$id, "o estado mudou para:".$myJSON);
        }
    }
    public function FazPublish($canal,$msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }

}
