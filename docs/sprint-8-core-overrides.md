# Sprint 8 — Arquivos do núcleo copiados para o tema

## Objetivo

Registrar os arquivos nativos do Magento copiados para o tema:

```text
Webjump/noite-assombrada
```

Esses arquivos devem ser revisados em futuras atualizações do Magento, pois passam a manter uma versão própria da estrutura original.

---

## 1. Minicart

Arquivo no tema:

```text
Magento_Checkout/web/template/minicart/content.html
```

Origem:

```text
vendor/magento/module-checkout/view/frontend/web/template/minicart/content.html
```

Motivo:

Adicionar a mensagem temática do minicart e permitir a utilização do binding customizado da campanha.

A lógica principal do minicart continua sendo estendida por mixin.

---

## 2. E-mail de novo pedido

Arquivo no tema:

```text
Magento_Sales/email/order_new.html
```

Origem:

```text
vendor/magento/module-sales/view/frontend/email/order_new.html
```

Motivo:

Adaptar o conteúdo do e-mail de confirmação de pedido à identidade da campanha `Noite Assombrada`.

A aparência complementar permanece em:

```text
web/css/source/_email-extend.less
```

---

## 3. Copyright

Arquivo no tema:

```text
Magento_Theme/templates/html/copyright.phtml
```

Origem:

```text
vendor/magento/module-theme/view/frontend/templates/html/copyright.phtml
```

Motivo:

Personalizar o texto exibido no footer para a identidade da loja e da campanha.

---

## Arquivos próprios do tema

Os seguintes arquivos encontrados no mesmo namespace não são cópias do núcleo:

```text
Magento_Theme/templates/html/campaign-strip.phtml
Magento_Theme/templates/html/countdown.phtml
Magento_Theme/templates/html/haunted-mode-toggle.phtml
Magento_Theme/web/template/countdown.html
Magento_Theme/layout/default.xml
Magento_Email/web/logo_email.png
```

Eles foram criados especificamente para o tema ou representam recursos próprios da campanha.

---

## Estratégia de manutenção

Em futuras atualizações do Magento, os três templates sobrescritos devem ser comparados com suas versões atuais em `vendor/`.

```text
Magento atualizado
       ↓
comparar template do núcleo
       ↓
verificar alterações
       ↓
atualizar override se necessário
```

Isso reduz o risco de manter uma versão antiga de um template após atualização do Magento.

---

## Resumo

```text
Templates nativos copiados: 3

1. Magento_Checkout/web/template/minicart/content.html
2. Magento_Sales/email/order_new.html
3. Magento_Theme/templates/html/copyright.phtml
```

Nenhum arquivo do núcleo foi alterado diretamente.

**Status: Concluído ✅**