<?php

namespace app\websockets;

use Workerman\Connection\TcpConnection;
class MyTcpConnection extends TcpConnection
{
        /**
     * Sends data on the connection.
     *
     * @param mixed $send_buffer
     * @param bool  $raw
     * @return bool|null
     */
    public function sendEncoded($send_buffer, $raw = false)
    {
        $this->send(json_encode($send_buffer),$raw);
    }

}
