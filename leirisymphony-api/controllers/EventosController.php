<?php

namespace app\controllers;

use Bluerhinos\phpMQTT;
use yii\rest\ActiveController;

class EventosController extends ActiveController
{
    public $modelClass = 'app\models\Eventos';
}
