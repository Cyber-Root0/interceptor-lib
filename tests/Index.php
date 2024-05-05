<?php
use CR0\Interceptor\Example\Customer;
use CR0\Interceptor\Example\AspectExample;
use CR0\Interceptor\Kernel;
require_once(__DIR__.'/../vendor/autoload.php');

$kernel = Kernel::getInstance();
$kernel->addProxy(Customer::class, AspectExample::class);
$container = $kernel->build();
$customer = $container->get(Customer::class);
echo $customer->teste('Primeiro Teste', 249);