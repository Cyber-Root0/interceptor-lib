<?php
namespace CR0\Interceptor\Example;
class AspectExample
{
    public function aroundTeste($subject,  $string, $int) : string{
        return 'Opa opa opa';
    }
}
