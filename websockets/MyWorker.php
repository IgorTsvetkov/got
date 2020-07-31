<?php 
namespace app\websockets;

use Workerman\Worker;
use Workerman\Connection\UdpConnection;
use Workerman\Connection\ConnectionInterface;

class MyWorker extends Worker{
    public $onMessageEncoded;
    public function __construct($socket_name = '', array $context_option = array())
    {
        parent::__construct($socket_name,$context_option);
        $this->onMessage=function($connection,$data){
            if(isset($onMessageEncoded)){            
                $encodedData=json_encode($data);
                call_user_func($this->onMessageEncoded,$connection,$encodedData);
            }
        };
    }
    public function acceptUdpConnection($socket)
    {
        \set_error_handler(function(){});
        $recv_buffer = \stream_socket_recvfrom($socket, static::MAX_UDP_PACKAGE_SIZE, 0, $remote_address);
        \restore_error_handler();
        if (false === $recv_buffer || empty($remote_address)) {
            return false;
        }
        // UdpConnection.
        $connection           = new MyUdpConnection($socket, $remote_address);
        $connection->protocol = $this->protocol;
        if ($this->onMessage) {
            try {
                if ($this->protocol !== null) {
                    /** @var \Workerman\Protocols\ProtocolInterface $parser */
                    $parser      = $this->protocol;
                    if(\method_exists($parser,'input')){
                        while($recv_buffer !== ''){
                            $len = $parser::input($recv_buffer, $connection);
                            if($len === 0)
                                return true;
                            $package = \substr($recv_buffer,0,$len);
                            $recv_buffer = \substr($recv_buffer,$len);
                            $data = $parser::decode($package,$connection);
                            if ($data === false)
                                continue;
                            \call_user_func($this->onMessage, $connection, $data);
                        }
                    }else{
                        $data = $parser::decode($recv_buffer, $connection);
                        // Discard bad packets.
                        if ($data === false)
                            return true;
                        \call_user_func($this->onMessage, $connection, $data);
                    }
                }else{
                    \call_user_func($this->onMessage, $connection, $recv_buffer);
                }
                ++ConnectionInterface::$statistics['total_request'];
            } catch (\Exception $e) {
                static::log($e);
                exit(250);
            } catch (\Error $e) {
                static::log($e);
                exit(250);
            }
        }
        return true;
    }
}