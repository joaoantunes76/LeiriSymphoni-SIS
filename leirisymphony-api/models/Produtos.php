<?php

namespace app\models;

use Bluerhinos\phpMQTT;
use Yii;

/**
 * This is the model class for table "produtos".
 *
 * @property int $id
 * @property int $idsubcategoria
 * @property int $idmarca
 * @property string $nome
 * @property string $descricao
 * @property int $usado
 * @property float $preco
 * @property int $stock
 *
 * @property Avaliacao[] $avaliacaos
 * @property Carrinho[] $carrinhos
 * @property Demonstracoes[] $demonstracoes
 * @property Encomendasprodutos[] $encomendasprodutos
 * @property Encomendas[] $idencomendas
 * @property Marcas $idmarca0
 * @property Perfis[] $idperfils
 * @property Perfis[] $idperfils0
 * @property Perfis[] $idperfils1
 * @property Subcategorias $idsubcategoria0
 * @property Imagens[] $imagens
 * @property Produtosfavoritos[] $produtosfavoritos
 */
class Produtos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsubcategoria', 'idmarca', 'nome', 'descricao', 'usado', 'preco', 'stock'], 'required'],
            [['idsubcategoria', 'idmarca', 'usado', 'stock'], 'integer'],
            [['descricao'], 'string'],
            [['preco'], 'number'],
            [['nome'], 'string', 'max' => 45],
            [['idmarca'], 'exist', 'skipOnError' => true, 'targetClass' => Marcas::className(), 'targetAttribute' => ['idmarca' => 'id']],
            [['idsubcategoria'], 'exist', 'skipOnError' => true, 'targetClass' => Subcategorias::className(), 'targetAttribute' => ['idsubcategoria' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idsubcategoria' => 'Idsubcategoria',
            'idmarca' => 'Idmarca',
            'nome' => 'Nome',
            'descricao' => 'Descricao',
            'usado' => 'Usado',
            'preco' => 'Preco',
            'stock' => 'Stock',
        ];
    }

    /**
     * Gets query for [[Avaliacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacaos()
    {
        return $this->hasMany(Avaliacao::className(), ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Carrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasMany(Carrinho::className(), ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Demonstracoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDemonstracoes()
    {
        return $this->hasMany(Demonstracoes::className(), ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Encomendasprodutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendasprodutos()
    {
        return $this->hasMany(Encomendasprodutos::className(), ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Idencomendas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdencomendas()
    {
        return $this->hasMany(Encomendas::className(), ['id' => 'idencomenda'])->viaTable('encomendasprodutos', ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Idmarca0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmarca0()
    {
        return $this->hasOne(Marcas::className(), ['id' => 'idmarca']);
    }

    /**
     * Gets query for [[Idperfils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfils()
    {
        return $this->hasMany(Perfis::className(), ['id' => 'idperfil'])->viaTable('avaliacao', ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Idperfils0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfils0()
    {
        return $this->hasMany(Perfis::className(), ['id' => 'idperfil'])->viaTable('carrinho', ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Idperfils1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperfils1()
    {
        return $this->hasMany(Perfis::className(), ['id' => 'idperfil'])->viaTable('produtosfavoritos', ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Idsubcategoria0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdsubcategoria0()
    {
        return $this->hasOne(Subcategorias::className(), ['id' => 'idsubcategoria']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasMany(Imagens::className(), ['idproduto' => 'id']);
    }

    /**
     * Gets query for [[Produtosfavoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutosfavoritos()
    {
        return $this->hasMany(Produtosfavoritos::className(), ['idproduto' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $produtoId = $this->id;
        $myObj = new \stdClass();
        $myObj->preco = $this->preco;
        $myJSON = json_encode($myObj->preco);
        if (!$insert && isset($changedAttributes['preco']) && $changedAttributes['preco'] > $this->preco) {
            $this->FazPublish("Produto".$produtoId, "O preco do produto com id:".$produtoId." baixou para:".$myJSON."€");
        }
    }

    public function FazPublish($canal, $msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
}
