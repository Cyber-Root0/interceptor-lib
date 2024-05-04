<?php
namespace CR0\Interceptor\Code\Builder;
use Nette\PhpGenerator\ClassType;
use ReflectionMethod;
use ReflectionClass;
class MethodsBuilder
{
    private $reflectionObject;
    public const VISIBILITY  = ReflectionMethod::IS_PUBLIC;
    public function __construct(
        protected ClassType $proxy,
        protected string $origemclass
    ){
        $this->reflectionObject = new ReflectionClass($this->origemclass);
    }
    public function execute() : ClassType{
        $this->setMethods();
        return $this->proxy;
    }
    private function setMethods(){
        $methods = $this->getMethods();
        foreach($methods as $method){
            $methodName = $method->getName();
            if (!$this->rules($methodName)){
                continue;
            }
            $methodinfo = new ReflectionMethod($this->origemclass, $method->getName());
            $methodinfo->getAttributes();
            $this->createMethod($methodName, $methodinfo);
        }
    }
    private function createMethod($methodName, $methodInfo){
        $parameters = $methodInfo->getParameters();
        $method = $this->proxy
            ->addMethod($methodName)
	        ->addComment($methodName." intercepted")
            ->setPublic()
	        ->setReturnType($methodInfo->getReturnType())
	        ->setBody('return $this->__callAspect(func_num_args() > 0 ? func_get_args() : []);');
        $this->setParams($method, $parameters);
    }
    private function setParams($method, $parameters){
        foreach($parameters as $param){
            $type = $param->getType();
            $name = $param->getName();
            $defaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : 'without';
            if ($defaultValue != "without"){
                $method->addParameter($name, $defaultValue)
                ->setType($type);
            }else{
                $method->addParameter($name)
                ->setType($type);
            }
        }
    }
    private function rules(string $methodname){
        if ($methodname == "__construct" || $methodname == "__destruct"){
            return false;
        }
        return true;
    }
    private function getMethods(){
        $methods = $this->reflectionObject->getMethods(self::VISIBILITY);
        return $methods;
    }
}