<?php
namespace CR0\Interceptor\Example;
class Customer
{
    public function teste(string $name, int $idade) : string{
        return "Nome: $name\n Idade: $idade";
    }

}
