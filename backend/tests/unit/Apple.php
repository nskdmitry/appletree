<?php

namespace app\models;

use app\models\Apple;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

class AppleCase extends TestCase
{
    public function fallAppleFromTree()
    {
        $model = $this->growApple();
        $this->assertNull($model->falled, "Apple at tree");
        $model->fallToGround();
        $this->assertNotNull($model->falled, "Apple is falled");
    }

    public function appleAlreadyFalled()
    {
        $model = $this->growApple(true, 100);
        $this->assertNotNull($model->falled, "Apple is falled already");
        try {
            $model->fallToGround();
            $this->assertNotNull($model->falled, "Apple is falled");
        } catch (Exception $e) {
            $this->assertNotNull($e, "Do not refall");
        }
    }

    public function eatApple()
    {
        $model = $this->growApple(true, 100);
        $this->assertNotNull($model->falled, "Apple is not on tree");
        $model->eat(0.4);
        $this->assertEqualsWithDelta($model->size, 0.6, 0.1, "Bitted");
    }

    public function eatOnTree()
    {
        $model = $this->growApple(false, 2900);
        $this->assertNotNull($model->falled, "Apple is on tree");
        try {
            $model->eat(0.4);
            $this->assertEqualsWithDelta($model->size, 0.6, 0.1, "Bitted?");
        } catch (Exception $e) {
            $this->assertNotNull($e, "Apple can not be eated");
        }
    }

    public function eatRottenApple()
    {
        $model = $this->growApple(true, 98745);
        $this->assertNull($model->falled, "Apple is on tree");
        try {
            $model->eat(0.4);
            $this->assertEqualsWithDelta($model->size, 0.6, 0.1, "Bitted?");
        } catch (Exception $e) {
            $this->assertNotNull($e, $e->getMessage());
        }
    }

    protected function growApple(bool $falled=false, int $ago = 0): Apple
    {
        $model = new Apple();
        $model->created = time() - 10900;
        $model->color = array_rand(["green", "yellow", "red", "brown"]);
        $model->falled = $falled ? time() - $ago : null;
        $model->status = $model->falled === null ? Apple::$STATUS_UP 
            : ((time() - $this->created > 5 * 60 * 60) 
                ? Apple::$STATUS_LAY : Apple::$STATUS_ROTTEN);
         return $model;
    }
}