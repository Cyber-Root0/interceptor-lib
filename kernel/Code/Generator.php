<?php
namespace Cr0\Interceptor\Code;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use CR0\Interceptor\Aspect\Base;
use CR0\Interceptor\Kernel;

class Generator
{
    public string $ext = '.php';
    public string $path = __DIR__.'/../../generated/';
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
        if (!$this->isCache()){
            $this->clearFolder();
            foreach ($definitions as $origem => $aspect) {
                $proxyname = $aspect['proxyname'];
                $aspect = $aspect['aspect'];
                $this->generate($origem, $aspect, $proxyname);
            }
        }
    }
    public function isCache(){
        return true;
    }
    public function clearFolder(){
        $files = glob($this->path.'/*');  
        foreach($files as $file) { 
            if(is_file($file)){
                unlink($file);
            }   
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
        $finalClassname = explode('\\', $proxyname);
        $finalClassname = $finalClassname[count($finalClassname) - 1];

        $namespace = new PhpNamespace('CR0\Generated');

        $object = $namespace->addClass($finalClassname);
        $object->setExtends($origem);
        $object->addTrait(Base::class);
        $object = $this->setMethods($object, $aspect);
        $namespace->add($object);
        $bodyContent = $namespace->__toString();
        $bodyContent = '<?php'."\n".$bodyContent;
        $this->save($bodyContent, $finalClassname);
        return '';
    }
    /**
     * change public methods to private, for call method magics on proxy
     *
     * @param  object $object
     * @param  string $aspect
     * @return ClassType
     */
    private function setMethods($object, string $aspect)
    {
        $clientReflection = new \ReflectionClass($aspect);
        $methods = $clientReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        return $object;
        /*
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
        */
    }
    private function save($content, $fileName){
        $file = $this->getFileName($fileName);
        file_put_contents($file, $content);
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
    private function getFileName(string $class){
        return $this->path.$class.$this->ext;
    }
}