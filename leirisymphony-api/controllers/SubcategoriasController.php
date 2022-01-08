<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Subcategorias;
use yii\rest\ActiveController;

class SubcategoriasController extends ActiveController
{
    public $modelClass = 'app\models\Subcategorias';

    public function actionComCategoria(){
        $subcategorias = Subcategorias::find()->all();

        $dados = array();

        foreach ($subcategorias as $subcategoria) {
            $myObj = new \stdClass();
            $dados[] = array(
                $myObj->id = $subcategoria->id,
                $myObj->categoria = $subcategoria->idcategoria0->nome,
                $myObj->subcategoria = $subcategoria->nome
            );
        }
        return $dados;
    }


}
