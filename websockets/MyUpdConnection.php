<?php
namespace app\websockets;

use Workerman\Connection\UdpConnection;

/**
 * MyUdpConnection.
 */
class MyUdpConnection extends UdpConnection
{    
        /**
     * Sends JSON ecoded data on the connection.
     *
     * @param string $send_buffer
     * @param bool   $raw
     * @return void|boolean
     */
    public function sendEncoded($send_buffer, $raw = false)
    {
        $this->send(json_encode($send_buffer),$raw);
    }
}
