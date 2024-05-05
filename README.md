Interceptor PHP - Documentação
------------------------------

### Introdução

A Interceptor PHP é uma biblioteca para PHP que oferece um contêiner de injeção de dependência para a instância de classes, juntamente com a funcionalidade de interceptar objetos PHP. Baseada nos princípios do AOP (Aspect-Oriented Programming), permite a execução de métodos antes, após ou em substituição aos métodos originais das classes.

### Instalação

Você pode instalar a Interceptor PHP via Composer. Execute o seguinte comando no terminal:


`composer require cr0/interceptor-php`

### Uso Básico

Aqui está um exemplo básico de como utilizar a biblioteca :

``` php
use Interceptor\Kernel; 
use SeuNamespace\Customer; 
use SeuNamespace\AspectExample;  
// Obtenha uma instância do Kernel 
$kernel = Kernel::getInstance();  
// Adicione um proxy para a classe Customer e seu aspecto 
$kernel->addProxy(Customer::class, AspectExample::class);  
// Construa o contêiner de injeção de dependência 
$container = $kernel->build();  
// Obtenha uma instância da classe Customer do contêiner 
$customer = $container->get(Customer::class);  
// Chame um método da classe Customer 
echo $customer->getName('Bruno');


```


### Exemplo de Aspecto (Aspect)

Aqui está um exemplo básico de como definir um aspecto para interceptar métodos da classe Customer:

``` php

use SeuNamespace\Customer;  
class AspectExample {     
    public function beforeGetName(Customer $subject, string $name){ 
        echo "Calling before GetName Method";            
        // Executa antes do método getName() e retorna um array com parametros      alterados        
        return [             "Nome substituido"         
        ];     
    }      
    public function aroundGetName(Customer $subject, string $name) : string     {  
        echo "Calling around GetName Method";       
        // Substitui o método getName() e retorna um valor personalizado         
        return "Teste 123";     
    }      
    public function afterGetName(Customer $subject, $out)     { 
        echo "Calling after GetName Method";            
        // Executa após o método getName()         
        echo $out;         
        return $out;     
    } 
}

```

### Métodos Disponíveis

*   `beforeMethodName(ObjetoClasse $objeto, ...$args)`: Executa antes do método `methodName()`.
*   `aroundMethodName(ObjetoClasse $objeto, ...$args)`: Substitui o método `methodName()` e pode retornar um valor personalizado.
*   `afterMethodName(ObjetoClasse $objeto, $retorno)`: Executa após o método `methodName()`.

### Considerações Finais

A Interceptor PHP Lib oferece uma maneira elegante de adicionar lógica adicional aos métodos de suas classes, seguindo os princípios do AOP. Isso facilita a separação de preocupações e a reutilização de aspectos em várias partes do código. Experimente e descubra como ela pode melhorar sua estrutura de aplicativo PHP.
