<?php

namespace app\controllers;

use app\models\Encomendas;
use app\models\Encomendasprodutos;
use Bluerhinos\phpMQTT;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Json;
use yii\rest\ActiveController;


class EncomendasController extends ActiveController
{
    public $modelClass = 'app\models\Encomendas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index' ,'view' ,'create', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

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
