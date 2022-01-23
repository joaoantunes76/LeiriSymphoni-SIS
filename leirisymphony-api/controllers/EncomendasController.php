<?php

namespace app\controllers;

use app\models\Carrinho;
use app\models\Encomendas;
use app\models\Encomendasprodutos;
use app\models\Perfis;
use app\models\Produtos;
use app\models\User;
use Bluerhinos\phpMQTT;
use phpDocumentor\Reflection\Types\Array_;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Json;
use yii\rest\ActiveController;


class EncomendasController extends ActiveController
{
    public $modelClass = 'app\models\Encomendas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index' ,'view' ,'create', 'delete'];
        $behaviors['authenticator']["authMethods"] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
            QueryParamAuth::class,
        ];
        return $behaviors;
    }

    public function actionTotal(){
        $total = count(Encomendas::find()->all());
        return $total;
    }

    public function actionPorUtilizador($idperfil){
        $Encomenda = Encomendas::find()->where(['idperfil' => $idperfil])->all();
       
        return $Encomenda;
    }
    
    public function actionNaoPago(){
       $Encomenda = Encomendas::find()->where(['pago' => 0 ])->all();
       

        return $Encomenda;
    }

    public function actionCriarEncomenda(){
        $token = substr(Yii::$app->request->headers["authorization"], 7);
        if(User::findIdentityByAccessToken($token)) {
            $user = User::findIdentityByAccessToken($token);
            $perfil = Perfis::find()->where(['iduser' => $user->id])->one();

            $carrinhos = Carrinho::find()->where(['idperfil' => $perfil->id])->all();
            $preco = 0;
            foreach ($carrinhos as $carrinho) {
                $produto = Produtos::find()->where(["id" => $carrinho->idproduto])->one();
                $preco += $produto->preco;
            }

            $encomenda = new Encomendas();
            $encomenda->idperfil = $perfil->id;
            $encomenda->estado = "Em Processamento";
            $encomenda->pago = $this->request->post("pago");
            $encomenda->preco = $preco;
            $encomenda->tipoexpedicao = $this->request->post("tipoexpedicao");
            $encomenda->data = date('Y-m-d');

            if($encomenda->validate() && $encomenda->save()) {
                foreach ($carrinhos as $carrinho) {
                    $encomendaProduto = new Encomendasprodutos();
                    $encomendaProduto->idproduto = $carrinho->idproduto;
                    $encomendaProduto->quantidade = $carrinho->quantidade;
                    $encomendaProduto->idencomenda = $encomenda->id;
                    $encomendaProduto->save();
                }
                $myObj = new \stdClass();
                $myObj->status = "Encomenda feita com sucesso";
                return $myObj;
            }
            else{
                $myObj = new \stdClass();
                $myObj->status = "Encomenda não foi criada";
                $myObj->error = $encomenda->firstErrors;
                return $myObj;
            }
        }
        else{
            $myObj = new \stdClass();
            $myObj->error = "Nenhum utilizador foi encontrado com esse token";
            return $myObj;
        }

    }

}
