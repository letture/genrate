<?php


namespace Lettrue\Genrate\Http;


class JsonResponse
{
    public static $lockKey = null;

    public static function success($data = [], $msg = '')
    {
        $msg = $msg ? $msg : '请求成功';
        //返回信息自动解锁
        if (self::$lockKey) {
            unlock(self::$lockKey);
        }
        return response()->json(['status' => 200, 'data' => $data, 'msg' => $msg, 'timestamp' => time()]);
    }

    public static function error($msg = '', $status = 400, $data = [], $name = '')
    {
        $msg = $msg ? $msg : '请求成功';
        //返回信息自动解锁
        if (self::$lockKey) {
            unlock(self::$lockKey);
        }
        return response()->json(['status' => $status, 'data' => $data, 'msg' => $msg, 'timestamp' => time()]);
    }
}