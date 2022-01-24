<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\Perfis;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class UsersController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'view', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

    public function actionLogin(){
        $model = new LoginForm();
        $model->username = $this->request->post("username");
        $model->password = $this->request->post("password");
        $myObj = new \stdClass();
        if ($model->login()) {
            $user = User::findByUsername($model->username);
            if($user->access_token == null){
                $key = Yii::$app->getSecurity()->generateRandomString().$model->username;
                $user->access_token = $key;
                $user->save();
            }
            $myObj->token = $user->access_token;

        }
        else {
            $myObj->error = "Error, username or password may be wrong!";
            $myObj->token = null;
        }
        return $myObj;
    }

    public function actionRegister(){
        $model = new SignupForm();

        $model->username = $this->request->post("username");
        $model->email = $this->request->post("email");
        $model->password = $this->request->post("password");
        $myObj = new \stdClass();
        if($model->validate()){
            $user = $model->signup();
            if($user->access_token == null){
                $key = Yii::$app->getSecurity()->generateRandomString().$model->username;
                $user->access_token = $key;
                $user->save();
            }
            $myObj->token = $user->access_token;
        }
        else{
            $myObj->error = $model->errors;
            $myObj->token = null;
        }
        return $myObj;
    }

    public function actionPerfil(){
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if(User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

            $myObj = new \stdClass();
            $myObj->email = $user->email;
            $myObj->nome = $perfil->nome;
            $myObj->nif = $perfil->nif;
            $myObj->endereco = $perfil->nif;
            $myObj->cidade = $perfil->nif;
            $myObj->codigopostal = $perfil->nif;
            $myObj->telefone = $perfil->nif;

            return $myObj;
        }
        else{
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }

    public function actionEditarPerfil(){
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if(User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

            $perfil->nome = $this->request->post("nome");
            $perfil->nif = $this->request->post("nif");
            $perfil->endereco = $this->request->post("endereco");
            $perfil->cidade = $this->request->post("cidade");
            $perfil->codigopostal = $this->request->post("codigopostal");
            $perfil->telefone = $this->request->post("telefone");

            if($perfil->validate() && $perfil->save()) {
                $myObj = new \stdClass();
                $myObj->email = $user->email;
                $myObj->nome = $perfil->nome;
                $myObj->nif = $perfil->nif;
                $myObj->endereco = $perfil->endereco;
                $myObj->cidade = $perfil->cidade;
                $myObj->codigopostal = $perfil->codigopostal;
                $myObj->telefone = $perfil->telefone;

                return $myObj;
            }
            else{
                $myObj = new \stdClass();
                $myObj->error = $perfil->errors;
                return $myObj;
            }
        }
        else{
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador encontrado com o token inserido";
            return $myObj;
        }
    }

}