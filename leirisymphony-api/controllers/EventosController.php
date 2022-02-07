<?php

namespace app\controllers;

use app\models\Eventos;
use app\models\Eventosperfis;
use app\models\Perfis;
use app\models\User;
use Bluerhinos\phpMQTT;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class EventosController extends ActiveController
{
    public $modelClass = 'app\models\Eventos';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'view', 'create', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

    public function actionGetEventoPerfil(){
        $token = substr(Yii::$app->request->headers["authorization"], 7);

        if ($token != false && User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
            $evento = Eventos::find()->where(['>', 'data', date('Y-m-d')])->orderBy(['data' => SORT_DESC])->one();
            if($evento != null) {
                $eventoPerfis = $evento->eventosperfis;
                foreach ($eventoPerfis as $eventoPerfil){
                    if($eventoPerfil->idperfil == $perfil->id){
                        return $eventoPerfil->idevento0;
                    }
                }
                return null;
            }
            else{
                $myObj = new \stdClass();
                $myObj->error = "Evento nao existe";
                return $myObj;
            }

        }
        else {
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }

    public function actionRegistarEventoPerfil(){
        $token = substr(Yii::$app->request->headers["authorization"], 7);

        if ($token != false && User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
            $idevento = $this->request->post("idevento");
            $evento = Eventos::find()->where(["id" => $idevento])->one();
            if($evento != null) {
                if (!Eventosperfis::find()->where(["idevento" => $idevento, "idperfil" => $perfil->id])->exists()) {
                    $eventoPerfil = new Eventosperfis();
                    $eventoPerfil->idevento = $idevento;
                    $eventoPerfil->idperfil = $perfil->id;
                    if ($eventoPerfil->validate() && $eventoPerfil->save()) {
                        $myObj = new \stdClass();
                        $myObj->status = "Utilizador registado no evento com sucesso";
                        return $myObj;
                    } else {
                        $myObj = new \stdClass();
                        $myObj->error = $eventoPerfil->firstErrors;
                        return $myObj;
                    }
                }
            }
            else{
                $myObj = new \stdClass();
                $myObj->error = "Evento nao existe";
                return $myObj;
            }

        }
        else {
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }
}
