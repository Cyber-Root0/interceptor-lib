<?php
namespace CR0\Interceptor\Aspect;
trait  Base
{    
    /**
     * intercept all public methods (pointcut) and insert
     * appropriate advices  
     *
     * @param array $method
     * @param array $args
     * @return void
     */
    public function __callAspect(...$args){
        $this->before();
        $this->after();
    }
    private function getParentClass(){
        
    }
    private function before(){
        echo "Antes da Execução";
    }
    private function after(){
        echo "Apos a execução";
    }
    public function get(){
        return $this->__callAspect(func_num_args() > 0 ? func_get_args() : []);
    }
    /*
    public function before($subject, $string $name){    
    return $name;
    }
    public function teste(string $name){
    }
    public function after(){
    }*/
}
