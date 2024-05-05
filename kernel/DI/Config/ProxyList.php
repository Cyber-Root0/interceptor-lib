<?php
namespace CR0\Interceptor\DI\Config;
class ProxyList
{
    private static array $proxylist = [];    
    /**
     * setList
     *
     * @param  array $list
     * @return void
     */
    public static function setList(array $list){
        self::$proxylist = $list;
    }    
    /**
     * return all list of aspect
     *
     * @return array
     */
    public static function getList(){
        return self::$proxylist;
    }    
    /**
     * get aspect class by the given class
     *
     * @param  mixed $key
     * @return string
     */
    public static function get(string $key){
        if (isset(self::$proxylist[$key])){
            return self::$proxylist[$key];
        }
        return '';
    }
}
