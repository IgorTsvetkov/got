<?php 
namespace app\websockets;

use Exception;
use Workerman\Worker;
use Workerman\Connection\UdpConnection;
use Workerman\Connection\ConnectionInterface;

class MyWorker extends Worker{
    public $onMessageDecoded;
    public function __construct($socket_name = '', array $context_option = array())
    {
        parent::__construct($socket_name,$context_option);
        $this->onMessage=function($connection ,$data){
            if(isset($this->onMessageDecoded)){            
                $decodedData=json_decode($data);
                call_user_func($this->onMessageDecoded,$connection,$decodedData);
            }
            else{
                throw new Exception("onMessageDecoded unset");
            }
        };
    }
    //Changed TcpConnection to MyTcpConnection


        /**
     * Write statistics data to disk.
     *
     * @return void
     */
    protected static function writeConnectionsStatisticsToStatusFile()
    {
        // For master process.
        if (static::$_masterPid === \posix_getpid()) {
            \file_put_contents(static::$_statisticsFile, "--------------------------------------------------------------------- WORKERMAN CONNECTION STATUS --------------------------------------------------------------------------------\n", \FILE_APPEND);
            \file_put_contents(static::$_statisticsFile, "PID      Worker          CID       Trans   Protocol        ipv4   ipv6   Recv-Q       Send-Q       Bytes-R      Bytes-W       Status         Local Address          Foreign Address\n", \FILE_APPEND);
            \chmod(static::$_statisticsFile, 0722);
            foreach (static::getAllWorkerPids() as $worker_pid) {
                \posix_kill($worker_pid, \SIGIO);
            }
            return;
        }

        // For child processes.
        $bytes_format = function($bytes)
        {
            if($bytes > 1024*1024*1024*1024) {
                return round($bytes/(1024*1024*1024*1024), 1)."TB";
            }
            if($bytes > 1024*1024*1024) {
                return round($bytes/(1024*1024*1024), 1)."GB";
            }
            if($bytes > 1024*1024) {
                return round($bytes/(1024*1024), 1)."MB";
            }
            if($bytes > 1024) {
                return round($bytes/(1024), 1)."KB";
            }
            return $bytes."B";
        };

        $pid = \posix_getpid();
        $str = '';
        \reset(static::$_workers);
        $current_worker = current(static::$_workers);
        $default_worker_name = $current_worker->name;

        /** @var \Workerman\Worker $worker */
        foreach(MyTcpConnection::$connections as $connection) {
            /** @var \Workerman\Connection\TcpConnection $connection */
            $transport      = $connection->transport;
            $ipv4           = $connection->isIpV4() ? ' 1' : ' 0';
            $ipv6           = $connection->isIpV6() ? ' 1' : ' 0';
            $recv_q         = $bytes_format($connection->getRecvBufferQueueSize());
            $send_q         = $bytes_format($connection->getSendBufferQueueSize());
            $local_address  = \trim($connection->getLocalAddress());
            $remote_address = \trim($connection->getRemoteAddress());
            $state          = $connection->getStatus(false);
            $bytes_read     = $bytes_format($connection->bytesRead);
            $bytes_written  = $bytes_format($connection->bytesWritten);
            $id             = $connection->id;
            $protocol       = $connection->protocol ? $connection->protocol : $connection->transport;
            $pos            = \strrpos($protocol, '\\');
            if ($pos) {
                $protocol = \substr($protocol, $pos+1);
            }
            if (\strlen($protocol) > 15) {
                $protocol = \substr($protocol, 0, 13) . '..';
            }
            $worker_name = isset($connection->worker) ? $connection->worker->name : $default_worker_name;
            if (\strlen($worker_name) > 14) {
                $worker_name = \substr($worker_name, 0, 12) . '..';
            }
            $str .= \str_pad($pid, 9) . \str_pad($worker_name, 16) .  \str_pad($id, 10) . \str_pad($transport, 8)
                . \str_pad($protocol, 16) . \str_pad($ipv4, 7) . \str_pad($ipv6, 7) . \str_pad($recv_q, 13)
                . \str_pad($send_q, 13) . \str_pad($bytes_read, 13) . \str_pad($bytes_written, 13) . ' '
                . \str_pad($state, 14) . ' ' . \str_pad($local_address, 22) . ' ' . \str_pad($remote_address, 22) ."\n";
        }
        if ($str) {
            \file_put_contents(static::$_statisticsFile, $str, \FILE_APPEND);
        }
    }
    public function acceptConnection($socket)
    {
        // Accept a connection on server socket.
        \set_error_handler(function(){});
        $new_socket = \stream_socket_accept($socket, 0, $remote_address);
        \restore_error_handler();

        // Thundering herd.
        if (!$new_socket) {
            return;
        }

        // TcpConnection.
        $connection                         = new MyTcpConnection($new_socket, $remote_address);
        $this->connections[$connection->id] = $connection;
        $connection->worker                 = $this;
        $connection->protocol               = $this->protocol;
        $connection->transport              = $this->transport;
        $connection->onMessage              = $this->onMessage;
        $connection->onClose                = $this->onClose;
        $connection->onError                = $this->onError;
        $connection->onBufferDrain          = $this->onBufferDrain;
        $connection->onBufferFull           = $this->onBufferFull;

        // Try to emit onConnect callback.
        if ($this->onConnect) {
            try {
                \call_user_func($this->onConnect, $connection);
            } catch (\Exception $e) {
                static::log($e);
                exit(250);
            } catch (\Error $e) {
                static::log($e);
                exit(250);
            }
        }
    }
    // public function acceptUdpConnection($socket)
    // {
    //     \set_error_handler(function(){});
    //     $recv_buffer = \stream_socket_recvfrom($socket, static::MAX_UDP_PACKAGE_SIZE, 0, $remote_address);
    //     \restore_error_handler();
    //     if (false === $recv_buffer || empty($remote_address)) {
    //         return false;
    //     }
    //     // UdpConnection.
    //     $connection           = new MyUdpConnection($socket, $remote_address);
    //     $connection->protocol = $this->protocol;
    //     if ($this->onMessage) {
    //         try {
    //             if ($this->protocol !== null) {
    //                 /** @var \Workerman\Protocols\ProtocolInterface $parser */
    //                 $parser      = $this->protocol;
    //                 if(\method_exists($parser,'input')){
    //                     while($recv_buffer !== ''){
    //                         $len = $parser::input($recv_buffer, $connection);
    //                         if($len === 0)
    //                             return true;
    //                         $package = \substr($recv_buffer,0,$len);
    //                         $recv_buffer = \substr($recv_buffer,$len);
    //                         $data = $parser::decode($package,$connection);
    //                         if ($data === false)
    //                             continue;
    //                         \call_user_func($this->onMessage, $connection, $data);
    //                     }
    //                 }else{
    //                     $data = $parser::decode($recv_buffer, $connection);
    //                     // Discard bad packets.
    //                     if ($data === false)
    //                         return true;
    //                     \call_user_func($this->onMessage, $connection, $data);
    //                 }
    //             }else{
    //                 \call_user_func($this->onMessage, $connection, $recv_buffer);
    //             }
    //             ++ConnectionInterface::$statistics['total_request'];
    //         } catch (\Exception $e) {
    //             static::log($e);
    //             exit(250);
    //         } catch (\Error $e) {
    //             static::log($e);
    //             exit(250);
    //         }
    //     }
    //     return true;
    // }
}