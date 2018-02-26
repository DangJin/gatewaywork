<?php
/**
 * Created by PhpStorm.
 * User: DangJin
 * Date: 2018-02-07
 * Time: 15:55
 */

class TcpClient
{

    public static $socket;
    public static $instance;

    /**
     * 防止从外部New
     * TcpClient constructor.
     */
    private function __construct()
    {
    }

    /**
     * 防止从外部 clone
     */
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 得到实例
     *
     * @return \TcpClient
     */
    static public function getInstace()
    {
        if ( ! (self::$instance instanceof self)) {
            self::$instance = new self();

            return self::$instance;
        }
    }

    /**
     * 创建Tcp 链接
     *
     * @param $host
     * @param $port
     *
     * @return resource
     */
    public function connect($host, $port)
    {
        if ( ! self::$socket) {
            self::$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect(self::$socket, $host, $port);
        }

        return self::$socket;
    }


}