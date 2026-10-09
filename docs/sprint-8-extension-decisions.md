# Sprint 8 — Sobrescrever, mesclar ou estender

## Objetivo

Registrar as decisões adotadas ao customizar o Magento durante a Sprint 8.

A prioridade foi utilizar o mecanismo de menor impacto possível:

```text
Mesclar
   ↓
Estender
   ↓
Sobrescrever quando necessário
```

---

## Mesclar

O Layout XML do tema foi utilizado para adicionar e reorganizar elementos sem substituir o layout original.

Principais casos:

- faixa da campanha `Noite Assombrada`;
- remoção de `catalog.compare.sidebar`;
- movimentação da navegação para o header;
- inclusão do `Modo Assombrado`;
- integração do contador de sustos.

Foi mantido o comportamento padrão de merge do Magento em vez de utilizar `layout/override`.

---

## Estender

Sempre que possível, o comportamento existente foi preservado e apenas complementado.

### Minicart

`Magento_Checkout/js/view/minicart` foi estendido através de mixin.

O componente original continua ativo e o comportamento da campanha é acrescentado sem substituição completa.

### Checkout

O campo `Mensagem Assombrada` foi inserido através de plugin no `LayoutProcessor`.

O valor é enviado por `extension_attributes`, persistido no quote e posteriormente copiado para o pedido.

### Selo sustentável

A listagem de produtos foi alterada por plugin, evitando sobrescrever `product/list.phtml`.

### Knockout

Foram utilizados:

- componente próprio para o countdown;
- binding reutilizável `shake`;
- componente Knockout para leitura de `customer-data`.

### Customer-data

A seção:

```text
haunted-scares
```

foi criada com `SectionSourceInterface`, `di.xml` e `sections.xml`.

Isso permite atualizar dados individuais do visitante sem renderizá-los diretamente no HTML cacheado.

### Page Builder

O `Caixão de Ofertas` foi implementado como um content type próprio, sem modificar os content types nativos.

---

## Sobrescrever

A sobrescrita de template foi utilizada somente quando era necessário alterar diretamente uma estrutura nativa.

Foram sobrescritos no tema:

```text
Magento_Theme/templates/html/copyright.phtml
Magento_Sales/email/order_new.html
Magento_Checkout/web/template/minicart/content.html
```

Nenhum arquivo foi alterado diretamente em:

```text
vendor/
```

---

## Estilos e identidade visual

A identidade do tema foi construída através de:

```text
_theme.less
_extend.less
components/*.less
```

As variáveis do Magento e a herança do Luma foram aproveitadas antes da criação de regras específicas.

---

## Resultado

As customizações da Sprint 8 priorizam extensão e mesclagem.

A sobrescrita foi mantida somente nos casos em que a estrutura completa de um template nativo precisava ser alterada.

**Status: Concluído ✅**