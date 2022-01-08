<?php

namespace app\controllers;

use app\models\Albuns;
use app\models\Artistas;
use Bluerhinos\phpMQTT;
use yii\helpers\Json;
use yii\rest\ActiveController;

class AlbunsController extends ActiveController
{
    public $modelClass = 'app\models\Albuns';

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
