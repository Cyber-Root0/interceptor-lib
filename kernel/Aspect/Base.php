<?php
namespace CR0\Interceptor\Aspect;
use CR0\Interceptor\DI\Config\ProxyList;
use CR0\Interceptor\DI\Config\Container;
trait  Base
{    
    /**
     * intercept all public methods (pointcut) and insert
     * appropriate advices  
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __callAspect(string $method, $args){
        $tempargs = $args;
        $key  = get_parent_class($this);
        $aspectClass = ProxyList::get($key);
        $methodCalled = ucfirst($method);
        $this->__instanceAspect($aspectClass);
        /* Set status of advices */
        $aspectBefore = $this->__verifyAspect('before', $methodCalled);
        $aspectAround = $this->__verifyAspect('around', $methodCalled);
        $aspectAfter = $this->__verifyAspect('after', $methodCalled);

        if ($aspectBefore){
            $call = 'before'.$methodCalled;
            /* resultBefore is a matriz of args or null */
            //$resultBefore = $this->__aspectobject->$call($this, $args);
            array_unshift($tempargs, $this);
            $resultBefore = call_user_func_array([
                $this->__aspectobject, $call
            ], $tempargs);
        }
        if ($aspectAround){
            $call = 'around'.$methodCalled;
            if (isset($resultBefore) && $resultBefore != null){
            //$result = $this->__aspectobject->$call($this, $resultBefore);
                array_unshift($resultBefore, $this);
                $result = call_user_func_array(
                    [
                        $this->__aspectobject, $call
                    ], $resultBefore
                ); 
            }else{
                $tempargs = $args;
                array_unshift($tempargs, $this);
                $result = call_user_func_array(
                    [
                        $this->__aspectobject, $call
                    ],
                    $tempargs
                );
            }   
        }else{
           if (isset($resultBefore)){
                $result = parent::$method(...$resultBefore);
           }else{
            $result = parent::$method(...$args);
           }
        }
        if ($aspectAfter){
            $call = 'after'.$methodCalled;
            $result = call_user_func_array(
                [
                    $this->__aspectobject, $call
                ],
                [
                    $this, $result
                ]
                );
        }
        return $result;
    }    
    /**
     * __verifyAspect
     *
     * @param string $aspect
     * @param string $method
     * @return bool
     */
    public function __verifyAspect(string $aspect, $method){
        if (method_exists($this->__aspectobject, $aspect.$method)){
            return true;
        }
        return false;
    }    
    /**
     * __instanceAspect
     *
     * @param  string $aspectclass
     * @return void
     */
    public function __instanceAspect(string $aspectclass){
        if ($this->__aspectobject === null){
            $this->__aspectobject = Container::get()->get($aspectclass);
        }
    }
}
