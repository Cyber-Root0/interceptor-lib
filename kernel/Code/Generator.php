<?php
namespace Cr0\Interceptor\Code;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use CR0\Interceptor\Code\Builder\MethodsBuilder;
use CR0\Interceptor\Aspect\Base;
use CR0\Interceptor\Kernel;

/**
 * Generator static files to intercept methods with aspect
 */
class Generator
{
    public string $ext = '.php';
    public string $path = __DIR__.'/../../generated/';
    public function __construct(
        protected Kernel $kernel
    ) {
    }
    /**
     * Main execute
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
    /**
     * define if can generate new files
     *
     * @return bool
     */
    public function isCache(){
        return false;
    }    
    /**
     * clear folder if is file
     *
     * @return void
     */
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
        $object = $this->setMethods($object, $origem);
        $object->addProperty('__aspectobject', null)->addComment('property to storage unic aspect instance');
        $namespace->add($object);
        $bodyContent = $namespace->__toString();
        $bodyContent = '<?php'."\n".$bodyContent;
        $this->save($bodyContent, $finalClassname);
        return '';
    }
    /**
     * change public methods to private, for call method magics on proxy
     *
     * @param  ClassType $object
     * @param  string $classorigem
     * @return ClassType
     */
    private function setMethods(ClassType $object, string $classorigem)
    {
        $builderMethods = new MethodsBuilder($object, $classorigem);
        return $builderMethods->execute();
    }    
    /**
     * save final file
     *
     * @param string $content
     * @param string $fileName
     * @return void
     */
    private function save($content, $fileName){
        $file = $this->getFileName($fileName);
        file_put_contents($file, $content);
    }    
    /**
     * get complete file name with path
     *
     * @param  mixed $methodName
     * @return string
     */
    private function getFileName(string $class){
        return $this->path.$class.$this->ext;
    }
}