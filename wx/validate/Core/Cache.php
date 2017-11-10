<?php

/**
 * 缓存类
 */
class Cache
{


    /**
     * 获取缓存
     * @param $fileName
     * @return mixed
     */
    public static function get($fileName)
    {
        $str = @file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName);
        return json_decode($str);
    }

    /**
     * 设置缓存
     * @param $fileName
     * @param $data
     */
    public static function put($fileName, $data)
    {
        file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName, json_encode($data));
    }

}
