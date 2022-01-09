<?php

namespace app\controllers;

use app\models\Encomendas;
use app\models\Encomendasprodutos;
use Bluerhinos\phpMQTT;
use yii\helpers\Json;
use yii\rest\ActiveController;


class EncomendasController extends ActiveController
{
    public $modelClass = 'app\models\Encomendas';

    public function actionTotal(){
        $total = count(Encomendas::find()->all());
        return $total;
    }

    public function actionPorUtilizador($idperfil){
        $Encomenda = Encomendas::find()->where(['idperfil' => $idperfil])->all();
       
        return $Encomenda;
    }
    
    public function actionNaoPago(){
       $Encomenda = Encomendas::find()->where(['pago' => 0 ])->all();
       

        return $Encomenda;
    }

}
