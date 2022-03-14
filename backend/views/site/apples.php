<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Apple;

/** @var yii\web\View $this */

$this->title = 'My Yii Application. Apples';
$apples = Apple::find()->all();

?>
<div class="site-index">

<div class="jumbotron text-center bg-transparent">
    <h1 class="display-4">Congratulations!</h1>

    <p class="lead">You have successfully created your Yii-powered application.</p>

    <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
</div>

<div class="body-content">
    <h2>Apples</h2>

    <div>
        <? $gen_form = ActiveForm::begin() ?>
            <p>Мало яблок?</p>
            <?= $gen_form->field(
                $generate_form, 
                'count', 
                [
                    'type' => 'number',
                    'placeholder' => 'Сколько добавить?'
                ]) ?>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-class']) ?>
        <? ActiveForm::end() ?>
    </div>

    <?php foreach($apples as $apple): ?>
    <div class="row" style="color: <?= $apple->color;?>">
        <div class="col-lg-4">
            <p>Объём: <?=$apple->size/100;?>, <?=$apple->status;?></p>
            <?php if($apple->status == Apple::$STATUS_LAY): ?>
                <p>Созрело <?=date("d.m.Y H:i:s", $apple->falled);?></p>
            <?php endif;?>
            <?php $form = ActiveForm::begin() ?>
                <?= Html::submitButton('Созрело', ['name' => 'down']); ?>
                <?= Html::submitButton('Откусить 10%', ['class' => 'btn btn-success', 'name' => 'snap10']); ?>
                <?= Html::submitButton('Откусить 50%', ['class' => 'btn btn-success', 'name' => 'snap50']) ?>
            <?php ActiveForm::end() ?>

            <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
        </div>
    </div>
    <?php endforeach;?>

</div>
</div>
