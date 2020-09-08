<?php

namespace app\models;

use Yii;
use app\models\Player;
use app\models\estate\Tax;
use app\models\estate\Utility;
use app\models\estate\Property;

/**
 * This is the model class for table "cell".
 *
 * @property int $id
 * @property int|null $position
 * @property string|null $event_id
 * @property int|null $property_id
 * @property int|null $tax_id
 * @property int|null $utility_id
 */
class Cell extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cell';
    }
    public function hasEqualPosition(Player $player):bool
    {
        return $this->position==$player->position;
    }
    public function getProperty()
    {
        return $this->hasOne(Property::class,["id"=>"property_id"]);
    }
    public function getEvent()
    {
        return $this->hasOne(Event::class,["id"=>"event_id"]);
    }
    public function getTax()
    {
        return $this->hasOne(Tax::class,["id"=>"tax_id"]);
    }
    public function getUtility()
    {
        return $this->hasOne(Utility::class,["id"=>"utility_id"]);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position'], 'integer'],
            [['event_id', 'property_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'event_id' => 'Event ID',
            'property_id' => 'Property ID',
            'tax_id' => 'Tax ID',
            'utility_id' => 'Utility ID',
        ];
    }
}
