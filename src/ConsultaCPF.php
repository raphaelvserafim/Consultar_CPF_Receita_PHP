<?php

namespace Cachesistemas\Consultacpf;

use DOMDocument;

class ConsultaCPF
{

    const host_cpf          = 'servicos.receita.fazenda.gov.br';
    const url_captcha       = 'https://servicos.receita.fazenda.gov.br/Servicos/CPF/ConsultaSituacao/ConsultaPublicaSonoro.asp';
    const pasta_cookie      = 'cookie';

    private $cookie;

    public function Setcookie()
    {

        $this->cookie  = self::pasta_cookie .  '/cookie_cpf_' . session_id();

        if (!file_exists($this->cookie)) {
            $file = fopen($this->cookie, 'w');
            chmod($this->cookie, 0777);
            fclose($file);
        } else {
            $conteudo = '';
            $cookie['cpf'] = '';
            $file = fopen($this->cookie, 'r');
            while (!feof($file)) {
                $conteudo .= fread($file, 1024);
            }
            fclose($file);
            $linha = explode("\n", $conteudo);
            for ($contador = 4; $contador < count($linha) - 1; $contador++) {
                $explodir = explode(chr(9), $linha[$contador]);
                $cookie['cpf'] .= trim($explodir[count($explodir) - 2]) . "=" . trim($explodir[count($explodir) - 1]) . "; ";
            }
            $cookie['cpf'] = substr($cookie['cpf'], 0, -2);
        }
    }


    public function imgRecaptchaCPF()
    {

        $this->Setcookie();

        $headers = array(
            0 => self::host_cpf,
            1 => 'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:53.0) Gecko/20100101 Firefox/53.0',
            2 => 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            3 => 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            4 => 'Connection: keep-alive',
            5 => 'Upgrade-Insecure-Requests: 1'
        );

        $ch = curl_init(self::url_captcha);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $doc = new DOMDocument();
        @$doc->loadHTML($result);
        $tags = $doc->getElementsByTagName('img');
        $count = 0;
        foreach ($tags as $tag) {
            $count++;
            if ($tag->getAttribute('id') == "imgCaptcha") {
                return array("status" => true, "img" => $tag->getAttribute('src'));
            }
        }
    }
}
