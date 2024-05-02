<?php
namespace CR0\Interceptor\DI\Config;
class ProxyList
{
    private static array $proxylist = [];
    public static function setList(array $list){
        self::$proxylist = $list;
    }
    public static function getList(){
        return self::$proxylist;
    }
}
