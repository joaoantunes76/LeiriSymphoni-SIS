<?php

namespace app\controllers;

use app\models\Carrinho;
use app\models\Perfis;
use app\models\Produtos;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'app\models\Carrinho';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['view', 'create', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

    public function actionCarrinhosByToken(){

        $token = substr(Yii::$app->request->headers["authorization"], 7);
        $user = User::findIdentityByAccessToken($token);
        $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

        $carrinho = Carrinho::find()->where(['idperfil' => $perfil->id])->all();
        return $carrinho;
    }

    public function actionAddCarrinhoByToken() {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        $user = User::findIdentityByAccessToken($token);
        $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

        if($this->request->post("idproduto") != null) {
            $idproduto = $this->request->post("idproduto");

            if (Produtos::find()->where(["id" => $idproduto])->exists()) {
                $carrinho = new Carrinho();
                $carrinho->idperfil = $perfil->id;
                $carrinho->idproduto = $idproduto;
                if ($carrinho->validate() && $carrinho->save()) {
                    return $carrinho;
                } else {
                    $myObj = new \stdClass();
                    $myObj->error = $carrinho->firstErrors;
                    return $myObj;
                }

            } else {
                $myObj = new \stdClass();
                $myObj->error = "Error, Produto does not exist";
                return $myObj;
            }
        }
        else{
            $myObj = new \stdClass();
            $myObj->error = "idproduto is missing";
            return $myObj;
        }
    }

    public function actionEditCarrinhoByToken($idproduto) {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        $user = User::findIdentityByAccessToken($token);
        $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

        if($this->request->post("quantidade") != null) {
            $quantidade = $this->request->post("quantidade");
            if (Carrinho::find()->where(["idproduto" => $idproduto, "idperfil" => $perfil->id])->exists()) {
                $carrinho = Carrinho::find()->where(["idproduto" => $idproduto, "idperfil" => $perfil->id])->one();
                $carrinho->quantidade = $quantidade;
                if ($carrinho->validate() && $carrinho->save()) {
                    return $carrinho;
                } else {
                    $myObj = new \stdClass();
                    $myObj->error = $carrinho->firstErrors;
                    return $myObj;
                }

            } else {
                $myObj = new \stdClass();
                $myObj->error = "Error, Produto does not exist";
                return $myObj;
            }
        }
        else{
            $myObj = new \stdClass();
            $myObj->error = "quantidade is missing";
            return $myObj;
        }
    }

    public function actionDeleteCarrinhoByToken($idproduto) {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        $user = User::findIdentityByAccessToken($token);
        $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
        if (Carrinho::find()->where(["idproduto" => $idproduto, "idperfil" => $perfil->id])->exists()) {
            Carrinho::find()->where(["idproduto" => $idproduto, "idperfil" => $perfil->id])->one()->delete();
            return true;
        }
        else{
            return false;
        }
    }
}
