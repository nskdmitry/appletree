<?php
namespace app\models;

use DateTime;
use Error;
use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    public static $STATUS_UP = "UP";
    public static $STATUS_LAY = "Down";
    public static $STATUS_ROTTEN = "Rotten";

    public $id;
    public $color;
    public $created;
    public $falled;
    public $size;
    public $status;

    public function eat(int $percent)
    {
        if ($this->status == static::$STATUS_UP) {
            throw new Error(_("Apple up on tree, it is not eatable"));
        }
        if ($this->status == static::$STATUS_ROTTEN) {
            throw new Error(_("Apple is rotten and it is not eatable"));
        }
        $this->size = max(0.000, $this->size - $percent);
        $this->update(true, ['size']);
        if ($this->size < 0.00) {
            $this->remove();
        }
    }

    public function fallToGround()
    {
        if ($this->falled) {
            throw new Error(_("Apple alredy falled"), 1);
        }
        $this->falled = new DateTime();
        $this->update(true, ['falled']);
    }

    public function remove()
    {
        $this->delete();        
    }

    public function fields()
    {
        return [
            'id',
            'color',
            'created',
            'falled',
            'size',
            'status' => function () {
                return $this->falled === null ? static::$STATUS_UP 
                : ((time() - $this->created > 5 * 60 * 60) 
                    ? static::$STATUS_LAY : static::$STATUS_ROTTEN);
            }
        ];
    }

    public function rules()
    {
        return [
            ['color', 'string'],
            ['created', 'validateDates'],
            ['falled', 'validateDates'],
            ['size', 'validateSize']
        ];
    }

    public function validateDates($attribute, $parameters)
    {
        $value = $this->$attribute;
        if (!is_string($value) && $value != null && !is_int($value)) {
            $this->addError($attribute, _("Date can be numeric timestamp, string or null"));
        }

        $date = is_string($value) ? (new DateTime($value)) : intval($value);
        if ($date === null || $date < 0) {
            $this->addError($attribute, 'Invalid format of data/timestamp');
        }
        if (($attribute == "created" && $value > $this->falled) 
            || ($attribute == "falled" && $value < $this->created)
        ) {
            $this->addError($attribute, "Apple must be up on tree before it will falled");
        }
    }

    public function validateSize($attribute, $parameters)
    {
        $value = $this->$attribute;
        if ($value > 1.000 || $value < -0.000) {
            $this->addError($attribute, "It is percent of apple size value.");
        }

        if ($value < 1.0 && $this->status == static::$STATUS_UP) {
            $this->addError($attribute, "Apple is untouchable until it down");
        }
    }
}
