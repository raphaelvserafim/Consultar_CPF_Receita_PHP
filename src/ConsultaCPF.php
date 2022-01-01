<?php

namespace Cachesistemas\ConsultaCPF;

use DOMDocument;

class ConsultaCPF
{

    const host_cpf          = 'servicos.receita.fazenda.gov.br';
    const url_captcha       = 'https://servicos.receita.fazenda.gov.br/Servicos/CPF/ConsultaSituacao/ConsultaPublicaSonoro.asp';
    const url_consulta_cpf  = 'https://servicos.receita.fazenda.gov.br/Servicos/CPF/ConsultaSituacao/ConsultaPublicaExibir.asp';
    const pasta_cookie      = '../cookie';
    private $cookie;
   

    public function __construct()
    {
        $this->cookie  = self::pasta_cookie .  '/cookie_cpf_' . session_id();
    }

    public function Setcookie()
    {

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

    public function getCookie()
    {

        $conteudo = '';
        $cookie   = '';
        if (!file_exists($this->cookie)) {
            return false;
        } else {
            $file = fopen($this->cookie, 'r');
            while (!feof($file)) {
                $conteudo .= fread($file, 1024);
            }
            fclose($file);
            $linha = explode("\n", $conteudo);
            for ($contador = 4; $contador < count($linha) - 1; $contador++) {
                $explodir  = explode(chr(9), $linha[$contador]);
                $cookie   .= trim($explodir[count($explodir) - 2]) . "=" . trim($explodir[count($explodir) - 1]) . "; ";
            }
            unlink($this->cookie);
            return substr($cookie, 0, -2);
        }
    }





    public function consultandoCPF($cpf, $datanascim, $captcha)
    {

        $post = array(
            'txtTexto_captcha_serpro_gov_br'        => $captcha,
            'txtCPF'                                => $cpf,
            'txtDataNascimento'                     => $datanascim,
            'Enviar'                                => 'Consultar',
            'CPF'                                   => '',
            'NASCIMENTO'                            => ''
        );
        $post = http_build_query($post, '', '&');

        $headers = array(
            'Host: servicos.receita.fazenda.gov.br',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:53.0) Gecko/20100101 Firefox/53.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
        );
        $ch = curl_init(self::url_consulta_cpf);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIE, $this->getCookie());
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_REFERER, 'https://servicos.receita.fazenda.gov.br/Servicos/CPF/ConsultaSituacao/ConsultaPublicaSonoro.asp?CPF=&NASCIMENTO=');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        $campos = array(
            'N<sup>o</sup> do CPF: <b>',
            'Nome: <b>',
            'Data de Nascimento: <b>',
            'Cadastral: <b>',
            'Data da Inscri&ccedil;&atilde;o: <b>'
        );

        $html3 = $html;

        for ($i = 0; $i < count($campos); $i++) {
            $html2 = strstr($html, utf8_decode($campos[$i]));
            $resultado[] = trim($this->pega_o_que_interessa(utf8_decode($campos[$i]), '</b>', $html2));
            $html = $html2;
        }

        if (!$resultado[0]) {
            if (strstr($html3, 'CPF incorreto')) {
                return  array("status" => false, "mensagem" => "CPF incorreto");
            } else if (strstr($html3, 'não existe em nossa base de dados')) {
                return  array("status" => false, "mensagem" => "não existe em nossa base de dados");
            } else if (strstr($html3, 'Os caracteres da imagem não foram preenchidos corretamente')) {
                return  array("status" => false, "mensagem" => "Os caracteres da imagem não foram preenchidos corretamente");
            } else if (strstr($html3, 'Data de nascimento informada')) {
                return    array("status" => false, "mensagem" => "Data de nascimento informada não confere");
            } else {
                return array("status" => false, "mensagem" => "Algo deu errado");
            }
        } else {
            return  array("status" => true, "cpf" => $resultado[0], "nome" => $resultado[1], "data_nascimento" => $resultado[2], "situacao" => $resultado[3]);
        }
    }




    public function pega_o_que_interessa($inicio, $fim, $total)
    {
        $interesse = str_replace($inicio, '', str_replace(strstr(strstr($total, $inicio), $fim), '', strstr($total, $inicio)));
        return ($interesse);
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
                return array("status" => true, "img" => $tag->getAttribute('src')); //BASE64
            }
        }
    }
}

