# Consultando CPF Receita Federal

## Instalando via composer 
```
composer require cachesistemas/consultacpf

```


## Exemplos de uso 


###### imgRecaptcha.php
```php
<?php 

include_once 'vendor/autoload.php';

use Cachesistemas\ConsultaCPF\ConsultaCPF;

$consulta    = new ConsultaCPF();

$result      = $consulta->imgRecaptchaCPF();

if ($result["status"]) {
    echo  '<img src="' . $result["img"] . '" style="width: 200px;">';
}
 

```


###### consultandoCPF.php
```php
<?php

include_once 'vendor/autoload.php';

use Cachesistemas\ConsultaCPF\ConsultaCPF;

$consulta    = new ConsultaCPF();

$result      = $consulta->consultandoCPF('00000000000','dd/mm/YYYY', 'captcha');

echo json_encode($result); 


```
###### Exemplo retorno  JSON 
```json
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
##### Contato

<a href="https://wa.me/5566996852025"> 
<img src="https://img.shields.io/badge/WhatsApp-25D366?style=for-the-badge&logo=whatsapp&logoColor=white" /> 
</a>
<a href="https://www.instagram.com/raphaelvserafim"> 
<img src="https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white" /> 
</a>


