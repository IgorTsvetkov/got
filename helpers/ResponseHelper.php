<?php
namespace app\helpers;

use PHPUnit\Util\Log\JSON;
use Yii;
use yii\web\Response;

class ResponseHelper
{ 
    public static function Socket(string $socketAction,$data)
    {
        $data=["action"=>$socketAction,"data"=>$data];
        return static::_return($data);
    }
    public static function Success($data)
    {
        return static::_return($data);
    }
    public static function Error($message)
    {
        $error=["error" => ["message" => $message]];

        return static::_return($error);
    }
    public static function _return($data)
    {
        
        $response=Yii::$app->response;
        $response->statusCode=200;

        $response->format=Response::FORMAT_JSON;

        $response->data=$data;
        return $response;
    }
}

