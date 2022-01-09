<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Produtos;
use app\models\Subcategorias;
use yii\rest\ActiveController;

class ProdutosController extends ActiveController
{
    public $modelClass = 'app\models\Produtos';

    public function actionRecentes(){
        $produtos = Produtos::find()->orderBy(['id' => SORT_DESC])->limit(4)->all();

        return $produtos;
    }

    public function actionPorCategoria($categoriaid){
        $subcategorias = Subcategorias::find()->select('id')->where(['idcategoria' => $categoriaid])->all();
        $produtos = Produtos::find()->innerJoinWith('idsubcategoria0')->where(['idcategoria' => $categoriaid])->all();

        return $produtos;
    }

    public function actionFiltroPrecoMaisBaixo(){
        $produtos = Produtos::find()->orderBy(['preco' => SORT_ASC])->all();

        return $produtos;
    }

    public function actionFiltroPrecoMaisAlto(){
        $produtos = Produtos::find()->orderBy(['preco' => SORT_DESC])->all();

        return $produtos;
    }
}
