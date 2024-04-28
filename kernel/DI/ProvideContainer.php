<?php
namespace CR0\Interceptor\DI;
use CR0\Interceptor\Kernel;
use DI\Container;
class ProvideContainer
{
    public const initnamespace = "Cr0\\Generated\\";
    public function __construct(
        protected Kernel $kernel
    ){
    }    
    /**
     * provide DI Container
     *
     * @param array $proxys
     * @param array $definitions
     * @return Container
     */
    public function execute(array $proxys, array $definitions) : Container{
        
    }
    private function remakeDefinition(array $proxys, array $definitions){
        foreach($proxys as $class => $aspect){
            $definitions[$class] = self::initnamespace.$aspect;
        }
    }
}
