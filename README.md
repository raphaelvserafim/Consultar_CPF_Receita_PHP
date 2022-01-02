# Consultando CPF Receita Federal

## Instalando via composer 
```
composer require cachesistemas/consultacpf

```


## Exemplos de uso 


###### imgRecaptcha.php
```
<?php 

include_once 'vendor/autoload.php';

use Cachesistemas\Consultacpf\ConsultaCPF;

$consulta    = new ConsultaCPF();

$result      = $consulta->imgRecaptchaCPF();

if ($result["status"]) {
    echo  '<img src="' . $result["img"] . '" style="width: 200px;">';
}
 

```


###### consultandoCPF.php
```
<?php

include_once 'vendor/autoload.php';

use Cachesistemas\Consultacpf\ConsultaCPF;

$consulta    = new ConsultaCPF();

$result      = $consulta->consultandoCPF('00000000000','dd/mm/YYYY', 'captcha');

echo json_encode($result); 


```
###### Exemplo retorno  JSON 
```
{
"status":true,
"cpf":"000.000.000-00",
"nome":"NOME COMPLETO",
"data_nascimento":"dd\/mm\/YYYY",
"situacao":"REGULAR"
}
```


##### ☕ PIX
``` 
23.711.695/0001-15 

```
