<?php

namespace app\models;

use Bluerhinos\phpMQTT;
use Yii;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property int $lotacao
 * @property string $descricao
 * @property string $data
 * @property string $horainicio
 * @property string $horafim
 *
 * @property Eventosperfis[] $eventosperfis
 * @property Perfis[] $idperfils
 */
class Eventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotacao', 'descricao', 'data', 'horainicio', 'horafim'], 'required'],
            [['lotacao'], 'integer'],
            [['descricao'], 'string'],
            [['data', 'horainicio', 'horafim'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lotacao' => 'Lotacao',
            'descricao' => 'Descricao',
            'data' => 'Data',
            'horainicio' => 'Horainicio',
            'horafim' => 'Horafim',
        ];
    }

    /**
     * Gets query for [[Eventosperfis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventosperfis()
    {
        return $this->hasMany(Eventosperfis::className(), ['idevento' => 'id']);
    }

    /**
     * Gets query for [[Idperfils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfils()
    {
        return $this->hasMany(Perfis::className(), ['id' => 'idperfil'])->viaTable('eventosperfis', ['idevento' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $event_id = $this->id;
        $myObj = new \stdClass();
        $myObj->id = $event_id;
        $myObj->lotacao = $this->lotacao;
        $myObj->descricao = $this->descricao;
        $myObj->data = $this->data;
        $myObj->horainicio = $this->horainicio;
        $myObj->horafim = $this->horafim;
        $myJSON = json_encode($myObj);
        if ($insert) {
            $this->FazPublish("INSERT", $myJSON);
        } else {
            $this->FazPublish("UPDATE", $myJSON);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $prod_id = $this->id;
        $myObj = new \stdClass();
        $myObj->id = $prod_id;
        $myJSON = json_encode($myObj);
        $this->FazPublish("DELETE", $myJSON);
    }

    public function FazPublish($canal, $msg)
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
