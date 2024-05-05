<?php
namespace CR0\Interceptor\DI;
use CR0\Interceptor\DI\Config\Container as StorageContainer;
use CR0\Interceptor\Code\Generator;
use CR0\Interceptor\Kernel;
use DI\Container;
class ProvideContainer
{   
    private $codegenerate;
    private $finaldefination = [];
    public const namespace = "CR0\\Generated\\";
    public function __construct(
        protected Kernel $kernel
    ){
        $this->codegenerate = new Generator($this->kernel);
    }    
    /**
     * provide DI Container
     *
     * @param array $proxys
     * @param array $definitions
     * @return Container
     */
    public function execute() : Container{
        $definitions = $this->remakeDefinition($this->kernel->getProxys(), $this->kernel->getDefinitions());
        $this->codegenerate->execute($this->finaldefination);
        $container = new Container($definitions);
        StorageContainer::set($container);
        return $container;
    }    
    /**
     * update container definiation with new proxy definitions
     *
     * @param  array $proxys
     * @param  array $definitions
     * @return array
     */
    private function remakeDefinition(array $proxys, array $definitions){
        foreach($proxys as $class => $aspect){
            $finalname = $this->getFinalNameClass($aspect);
            $this->finaldefination[$class] = [
                'proxyname' => $finalname,
                'aspect' => $aspect
            ];
            //$definitions[$class] = $finalname;
            $definitions[$class] = function($container) use ($finalname){
                return new $finalname();
            };
        }
        return $definitions;
    }    
    /**
     * get final class name, example: CR0\Generated\ProductProxy
     *
     * @param  string $classname
     * @return string
     */
    private function getFinalNameClass(string $classname){
        $path = explode('\\', $classname);
        return self::namespace.$path[count($path)-1]."Proxy";
    }
}
