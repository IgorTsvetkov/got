<?php
namespace app\helpers;
class GameHelper
{
    public static function roll():int{
        return rand(1,6);
    }
}
