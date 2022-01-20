<?php

namespace app\controllers;

use app\models\LoginForm;
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
            if($user->acess_token == null){
                $key = Yii::$app->getSecurity()->generateRandomString().$model->username;
                $user->acess_token = $key;
                $user->save();
            }
            $myObj->token = $user->acess_token;

        }
        else {
            $myObj->error = "Error, username or password may be wrong!";
            $myObj->token = null;
        }
        return $myObj;
    }

}