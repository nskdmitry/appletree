<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class GenerateForm extends Model
{
    public $count;

    public function rules()
    {
        return [
            ['count', ['required', 'integer']]
        ];
    }
}