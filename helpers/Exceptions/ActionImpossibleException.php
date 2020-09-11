<?php
namespace app\helpers\exceptions;

use Throwable;
use yii\base\Exception;

class ActionDeniedException extends Exception
{
    public function __construct(string $message="",$code=0,?Throwable $previous = null) {
        $pre_message="This action is impossible.";
        parent::__construct($pre_message." ".$message,$code,$previous);
    }
}
