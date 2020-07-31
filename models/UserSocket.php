<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
class UserSocket extends User implements \yii\web\IdentityInterface
{
    public static function getUserByUserInfo($userinfo){
        return static::getUserHasAuthKey($userinfo->id,$userinfo->authKey);
    }
    public static function getUserHasAuthKey($id,$authKey){
        $user=User::findOne($id);
        if($user->validateAuthKey($authKey))
            return $user;
        return false;
    }
}
