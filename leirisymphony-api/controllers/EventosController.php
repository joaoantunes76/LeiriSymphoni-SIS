<?php

namespace app\controllers;

use Bluerhinos\phpMQTT;
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
}
