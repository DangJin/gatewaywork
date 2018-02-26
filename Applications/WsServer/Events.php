<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{


    public static function onConnect($client_id)
    {
        // 向所有人发送
        $host = "127.0.0.1";
        $port = 8282;
        global $socket;
        if (empty($socket)) {
            $socket = TcpClient::getInstace()->connect($host, $port);
        Gateway::sendToClient($client_id, "$client_id welcome\n\r");
        }
    }


    public static function onMessage($client_id, $message)
    {
        if ($GLOBALS['socket']) {
            // 数据指令解包
            $message_arr = str_split(str_replace(' ', '', trim($message)), 2);
            $cmd=null;
            for ($j = 0; $j < count($message_arr); $j++) {
                //socket_write($GLOBALS['socket'], bin2hex($message_arr[$j]));
                socket_write($GLOBALS['socket'],chr(hexdec($message_arr[$j])));
            }
        }
    }


    public static function onClose($client_id)
    {
        // 向所有人发送
        GateWay::sendToAll("$client_id logout");
    }
}
