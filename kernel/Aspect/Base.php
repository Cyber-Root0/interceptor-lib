<?php
namespace CR0\Interceptor\Aspect;
trait  Base
{    
    /**
     * __call
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function __call($method, $args){
        echo "Ta passando aqui em todas as chamadas";
        return $this->$method($args);
    }
    private function getParentClass(){
        
    }
}
