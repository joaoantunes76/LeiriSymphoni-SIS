<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Imagens;
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

    public function actionRecentesComImagem(){
        $produtos = Produtos::find()->orderBy(['id' => SORT_DESC])->limit(4)->all();
        
        $produtosComImagem = array();
        foreach($produtos as $produto){
            $myObj = new \stdClass();
            $imagens = Imagens::find()->where(['idproduto' => $produto->id])->all();
            $myObj->id = $produto->id;
            $myObj->idsubcategoria = $produto->idsubcategoria;
            $myObj->idmarca = $produto->idmarca;
            $myObj->nome = $produto->nome;
            $myObj->descricao = $produto->descricao;
            $myObj->usado = $produto->usado;
            $myObj->preco = $produto->preco;
            $myObj->stock = $produto->stock;
            $myObj->imagemPrincipal = base64_encode(file_get_contents(\Yii::getAlias('@imageurl').'/'.$imagens[0]->nome));
            array_push($produtosComImagem, $myObj);
        }

        return $produtosComImagem;
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

    public function actionProdutosComImagem(){
        $produtos = Produtos::find()->all();
        $produtosComImagem = array();
        foreach($produtos as $produto){
            $myObj = new \stdClass();
            $imagens = Imagens::find()->where(['idproduto' => $produto->id])->all();
            $myObj->id = $produto->id;
            $myObj->idsubcategoria = $produto->idsubcategoria;
            $myObj->idmarca = $produto->idmarca;
            $myObj->nome = $produto->nome;
            $myObj->descricao = $produto->descricao;
            $myObj->usado = $produto->usado;
            $myObj->preco = $produto->preco;
            $myObj->stock = $produto->stock;
            $myObj->imagemPrincipal = base64_encode(file_get_contents(\Yii::getAlias('@imageurl').'/'.$imagens[0]->nome));
            array_push($produtosComImagem, $myObj);
        }

        return $produtosComImagem;
    }
}
