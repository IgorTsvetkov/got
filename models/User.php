<?php

namespace app\models;

use Yii;
use app\models\Player;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\GameSession;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static function me():?self{
        return Yii::$app->user->identity;
    }
    public function getLastGame(){
        return $this->hasOne(GameSession::class,["id"=>"game_session_id"])
        ->orderBy(['created_at'=>SORT_DESC])
        ->viaTable("user_game_session",["user_id"=>"id"]);
    }
    public function hasActiveGame(){
        return $this->hasOne(GameSession::class,["id"=>"game_session_id"])
        ->viaTable("user_game_session",["user_id"=>"id"])
        ->where(["is","finished_at",new Expression("NULL")])
        ->exists();
    }
    public function getLastPlayer():?Player{
        return $this->hasMany(Player::class,["user_id"=>"id"])->orderBy(["id"=>SORT_DESC])->limit(1)->one();
    }
    public function getGameSessions(){
        return $this->hasMany(GameSession::class,["id"=>"game_session_id"])
        ->viaTable("user_game_session",["user_id"=>"id"]);
    }
    public function getPlayers(){
        return $this->hasMany(Player::class,["user_id"=>"id"]);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(["accessToken"=>$token]);
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(["username"=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password,$this->passwordHash);
    }
    public static function getUserHasAuthKey($id,$authKey){
        $user=User::findOne($id);
        if($user->validateAuthKey($authKey))
            return $user;
        return false;
    }
        /**
     * Gets query for [[UserGameSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGameSessions()
    {
        return $this->hasMany(UserGameSession::className(), ['user_id' => 'id']);
    }
}