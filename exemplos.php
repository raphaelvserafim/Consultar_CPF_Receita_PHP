<?php 

include_once 'vendor/autoload.php';


use Cachesistemas\Consultacpf\ConsultaCPF;

 $consulta = new ConsultaCPF();
  
 $result      = $consulta->imgRecaptchaCPF();

if ($result["status"]) {
    echo  '<img src="' . $result["img"] . '" style="width: 200px;">';
}
 

// $result      = $consulta->consultandoCPF('00000000000','dd/mm/YYYY', 'captcha');
// echo json_encode($result);

