# Consulta CPF Receita Federal

Biblioteca PHP para consulta de CPF na Receita Federal.

## ⚠️ Projeto descontinuado

Este projeto utilizava automação baseada em captcha da Receita Federal.  
Atualmente, a Receita possui diversas proteções e instabilidades que podem causar:

- bloqueios temporários
- falhas no captcha
- indisponibilidade frequente
- necessidade constante de manutenção
- risco de quebra sem aviso prévio

Por isso, hoje recomendo utilizar a API oficializada da plataforma:

👉 [Entrar API](https://entrar.api.br)

## ✅ Por que usar a Entrar API?

A [Entrar API](https://entrar.api.br) oferece uma solução muito mais estável e profissional para consultas de CPF/CNPJ.

### Vantagens

- Sem captcha
- API REST simples
- Muito mais estabilidade
- Melhor performance
- Integração rápida
- Ideal para sistemas ERP/CRM/SaaS
- Menor manutenção
- Menor risco de bloqueio
- Ambiente profissional para produção

## 📦 Instalação antiga (legado)

```bash
composer require cachesistemas/consultacpf
```

---

# Exemplos antigos de uso

## imgRecaptcha.php

```php
<?php 

include_once 'vendor/autoload.php';

use Cachesistemas\ConsultaCPF\ConsultaCPF;

$consulta = new ConsultaCPF();

$result = $consulta->imgRecaptchaCPF();

if ($result["status"]) {
    echo '<img src="' . $result["img"] . '" style="width: 200px;">';
}
```

---

## consultandoCPF.php

```php
<?php

include_once 'vendor/autoload.php';

use Cachesistemas\ConsultaCPF\ConsultaCPF;

$consulta = new ConsultaCPF();

$result = $consulta->consultandoCPF(
    '00000000000',
    'dd/mm/YYYY',
    'captcha'
);

echo json_encode($result);
```

---

## Exemplo retorno JSON

```json
{
  "status": true,
  "cpf": "000.000.000-00",
  "nome": "NOME COMPLETO",
  "data_nascimento": "dd/mm/YYYY",
  "situacao": "REGULAR"
}
```

---

# 🚀 Migração recomendada

Recomendo migrar novos projetos para:

👉 [https://entrar.api.br/](https://entrar.api.br)

Isso reduz drasticamente problemas de manutenção e aumenta a confiabilidade do sistema.

---

# ☕ PIX

```txt
23.711.695/0001-15
```

# 📞 Contato

- Instagram: [@raphaelvserafim](https://www.instagram.com/raphaelvserafim?utm_source=chatgpt.com)
- WhatsApp: [WhatsApp Raphael Serafim](https://wa.me/5566996852025?utm_source=chatgpt.com)
- Portfólio: [raphaelvserafim.com](https://raphaelvserafim.com/?utm_source=chatgpt.com)
