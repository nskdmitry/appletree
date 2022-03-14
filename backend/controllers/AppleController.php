<?php

namespace app\controllers;

use app\models\Apple;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\GenerateForm;
use Yii;

class AppleController extends Controller
{
    public function actionIndex()
    {
        return $this->showApplesList();
    }

    public function actionGrow()
    {
        $model = new Apple();
        $model->color = array_rand(["green", "yellow", "red", "brown"]);
        $model->size = 1.0;
        $model->save();
        return $this->showApplesList();
    }

    public function actionFall()
    {
        $model = new Apple();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model = Apple::findOne($model->id);
            $model->fallToGround();
            $model->save();
        }
        return $this->showApplesList();
    }

    public function actionAdd()
    {
        $data = Yii::$app->request->post();
        $count = intval($data->count);
        if ($count > 0) {
            for (; $count > 0; $count--) {
                $model = new Apple();
                $model->created = time() - 2000;
                $model->color = array_rand(['red', 'yellow', 'brown', 'marron', 'green']);
                $model->falled = random_int(0, 100) < 50 ? null : time() + 2000;
                $model->size = 1.0;
                $model->save();
            }
        }
        return $this->showApplesList();
    }

    public function actionEat(int $id, int $percent)
    {
        $data = Yii::$app->request->post();
        $id = intval($data['id']);
        $percent = !!$data['snap10'] ? 10 : (!!$data['snap50'] ? 50 : 0);
        if ($id > 0) {
            $model = Apple::findOne($id);
            try {
                $model->eat($percent);
            } catch (Exception $e) {}
        }
        return $this->showApplesList();
    }
   
    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => HttpBasicAuth::class,
            ],
        ];
    }

    protected function showApplesList()
    {
        $apples = Apple::find()->all();
        $generate_form = new GenerateForm();
        $generate_form->count = count($apples);
        return $this->render('apples', ['apples' => $apples, 'model' => $generate_form]);
    }
}