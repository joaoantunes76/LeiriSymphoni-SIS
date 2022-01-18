<?php

namespace app\controllers;

use app\models\Albuns;
use app\models\Artistas;
use Bluerhinos\phpMQTT;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Json;
use yii\rest\ActiveController;

class AlbunsController extends ActiveController
{
    public $modelClass = 'app\models\Albuns';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

    public function actionTotal(){
        $total = count(Albuns::find()->all());
        return $total;
    }

    public function actionArtistas($albumid){
        $album = Albuns::find()->where(['id' => $albumid])->one();
        $artistas = $album->idartistas;

        return $artistas;
    }

    public function actionComMusicas($id){
        $album = Albuns::find()->where(['id' => $id])->one();
        $musicas = $album->musicas;

        $myObj = new \stdClass();
        $myObj->id = $album->id;
        $myObj->nome = $album->nome;
        $myObj->preco = $album->preco;
        $myObj->datalancamento = $album->datalancamento;
        $myObj->idimagem = $album->idimagem;
        $myObj->musicas = $musicas;

        return $myObj;
    }
}
