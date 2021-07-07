<?php
if (! function_exists('lock')) {
    function lock($key, $value = 1, $expire = 31): bool
    {
        \Lettrue\Genrate\Http\JsonResponse::$lockKey = $key;
        if (config('cache.default') == 'redis' && class_exists('Redis')) {
            if (Redis::instance()->setnx($key, $value)) {
                Redis::instance()->expire($key, $expire);
                return true;
            }
            return false;
        }
        if (Cache::has($key)) {
            return false;
        }
        Cache::put($key, '1', $expire);
        $res = Cache::increment($key, 1);
        if ($res == 2) {
            return true;
        }
        return false;
    }
}

if (! function_exists('unlock')) {
    function unlock($key)
    {
        \Lettrue\Genrate\Http\JsonResponse::$lockKey = null; //避免两次解锁，如果在程序中调用unlock后在Format中返回时就不需要调用unlock
        if (config('cache.default') == 'redis' && class_exists('Redis')) {
            return Redis::instance()->del($key);
        }
        return Cache::forget($key);
    }
}