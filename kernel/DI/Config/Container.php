<?php
namespace CR0\Interceptor\DI\Config;
use DI\Container as DIContainer;
class Container
{
    private static DIContainer $container;        
    /**
     * set
     *
     * @param  DIContainer $container
     * @return void
     */
    public static function set(DIContainer $container){
        self::$container = $container;
    }    
    /**
     * get
     *
     * @return DIContainer
     */
    public static function get(){
        return self::$container;        
    }
}
