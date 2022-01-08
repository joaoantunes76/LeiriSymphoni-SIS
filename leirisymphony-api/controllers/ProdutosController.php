<?php

namespace app\controllers;

use app\models\Produtos;
use yii\rest\ActiveController;

class ProdutosController extends ActiveController
{
    public $modelClass = 'app\models\Produtos';

    public function actionRecentes(){
        $produtos = Produtos::find()->orderBy(['id' => SORT_DESC])->limit(4)->all();

        return $produtos;
    }

    public function actionFiltroPrecoMaisBaixo(){
        $produtos = Produtos::find()->orderBy(['preco' => SORT_ASC])->all();

        return $produtos;
    }

}
