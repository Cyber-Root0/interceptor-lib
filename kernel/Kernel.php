<?php
namespace CR0\Interceptor;
use CR0\Interceptor\DI\ProvideContainer;
use DI\Container;
class Kernel
{
    private static $instance = null;
    private array $definitions = [];
    private array $proxys = [];
    private $provider = null;
    /**
     * __construct
     *
     * @return void
     */
    private function __construct(array $definitions = []){
        if (!empty($definitions)){
            $this->$definitions = $definitions;
        }
        $this->provider = new ProvideContainer($this);
    }    
    /**
     * get the simple and shared instance
     *
     * @return self
     */
    public static function getInstance($definitions = []){
        if (self::$instance === null){
            self::$instance = new self($definitions);
        }
        return self::$instance;
    }
    public function addProxy(string $class, string $aspect){
        $this->validClass($class)->validClass($aspect);
        $this->proxys[$class] = $aspect;
    }    
    /**
     * valid if the class exists
     *
     * @param  string $className
     * @return self | \InvalidArgumentException
     */
    private function validClass(string $className) : self | \InvalidArgumentException{
        if (!class_exists($className)){
            throw new \InvalidArgumentException("Class {$className} don't exists");
        }
        return $this;
    }
    public function getProxys() : array{
        return $this->proxys;
    }
    public function getDefinitions() : array{
        return $this->definitions;
    }
    /**
     * build
     *
     * @return Container
     */
    public function build() : Container{
        return $this->provider->execute();
    }
}