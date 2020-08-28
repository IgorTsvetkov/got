<?php
namespace app\helpers;

use PHPUnit\Util\Log\JSON;
use Yii;
use yii\web\Response;

class ResponseHelper
{ 
    public static function Socket(string $socketAction,$data=null)
    {
        $data=["action"=>$socketAction,"data"=>$data];
        return static::_return($data);
    }
    public static function Success($data=null)
    {
        return static::_return($data);
    }
    public static function Error($message)
    {
        $data=["error" => ["message" => $message]];

        return static::_return($data);
    }
    public static function Redirect($url)
    {
        $data=["redirect"=>["url"=>$url]];
        return static::_return($data);
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

