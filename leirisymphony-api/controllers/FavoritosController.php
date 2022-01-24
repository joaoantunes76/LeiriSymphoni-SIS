<?php

namespace app\controllers;

use app\models\Perfis;
use app\models\Produtos;
use app\models\Produtosfavoritos;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class FavoritosController extends ActiveController
{
    public $modelClass = 'app\models\Produtosfavoritos';

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

    public function actionFavoritosPorToken()
    {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if (User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
            $favoritos = Produtosfavoritos::find()->where(["idperfil" => $perfil->id])->all();
            return $favoritos;
        } else {
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }

    public function actionAddFavoritoPorToken()
    {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if (User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
            $idproduto = $this->request->post("idproduto");
            if(!Produtosfavoritos::find()->where(["idperfil" => $perfil->id, "idproduto" => $idproduto])->exists()){
                $produtoFavorito = new Produtosfavoritos();
                if(Produtos::find()->where(["id" => $idproduto])->exists()) {
                    $produtoFavorito->idproduto = $idproduto;
                    $produtoFavorito->idperfil = $perfil->id;
                    $produtoFavorito->save();
                    if($produtoFavorito->save()) {
                        $myObj = new \stdClass();
                        $myObj->status = "Produto adicionado com sucesso";
                        return $myObj;
                    }
                    else{$myObj = new \stdClass();
                        $myObj->error = $produtoFavorito->errors;
                        return $myObj;
                    }
                }
                else{
                    $myObj = new \stdClass();
                    $myObj->error = "Produto não existe";
                    return $myObj;
                }
            }
            else{
                $myObj = new \stdClass();
                $myObj->error = "Produto já existe nos favoritos";
                return $myObj;
            }
        } else {
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }

    public function actionDeleteFavoritoPorToken()
    {
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if (User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();
            $idproduto = $this->request->post("idproduto");
            if(!Produtosfavoritos::find()->where(["idperfil" => $perfil->id, "idproduto" => $idproduto])->exists()){
                $myObj = new \stdClass();
                $myObj->error = "Produto não existe nos favoritos";
                return $myObj;
            }
            else{
                Produtosfavoritos::find()->where(["idperfil" => $perfil->id, "idproduto" => $idproduto])->one()->delete();
                $myObj = new \stdClass();
                $myObj->status = "Produto removido dos Favoritos com sucesso";
                return $myObj;
            }
        } else {
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }
}
