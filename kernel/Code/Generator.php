<?php
namespace Cr0\Interceptor\Code;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use CR0\Interceptor\Kernel;

class Generator
{
    public function __construct(
        protected Kernel $kernel
    ) {
    }
    /**
     * execute
     *
     * @return void
     */
    public function execute(array $definitions)
    {
        foreach ($definitions as $origem => $aspect) {
            $proxyname = $aspect['proxyname'];
            $aspect = $aspect['aspect'];
            $this->generate($origem, $aspect, $proxyname);
        }
    }
    /**
     * generate
     *
     * @param string $origem
     * @param string $aspect
     * @param string $proxyname
     * @return string
     */
    private function generate(string $origem, string $aspect, string $proxyname): string
    {
        $finalClassname = explode('\\', $aspect);
        $finalClassname = $finalClassname[count($finalClassname) - 1];

        $namespace = new PhpNamespace('CR0\Generated');

        $object = $namespace->addClass($finalClassname);
        $object->setExtends($origem);
        $object->addMethod('__call')->setBody(
            'return true;'
        );
        $this->setMethods($object, $aspect);
        //exit;
        $namespace->add($object);
        echo $namespace;

        return '';
    }
    /**
     * setMethods
     *
     * @param  object $object
     * @param  string $aspect
     * @return void
     */
    private function setMethods($object, string $aspect)
    {
        $clientReflection = new \ReflectionClass($aspect);
        $methods = $clientReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if ($this->rules($methodName)) {
                $fileName = $method->getFileName();
                $lines = file($fileName);
                $start = $method->getStartLine();
                $end = $method->getEndLine();
                $methodBody = implode("", array_slice($lines, $start - 1, $end - $start + 1));
                echo $methodBody."\n\n";
            }
        }
    }
    private function rules(string $methodName): bool
    {
        if (
            str_contains($methodName, 'before') ||
            str_contains($methodName, 'around') ||
            str_contains($methodName, 'after')
        ) {
            return true;
        }
        return false;
    }
}