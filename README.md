# 🛒 Magento Sprint AIR

Projeto desenvolvido durante o **Programa de Estágio da Webjump**, com foco em Magento 2 / Adobe Commerce.

O repositório reúne as atividades das **Sprints 6 e 7**, abordando configuração de ambiente, catálogo, CMS, desenvolvimento de módulos, extensão do Magento, EAV, banco de dados, Service Contracts e interfaces administrativas.

---

## 🛠️ Tecnologias

- Magento Open Source 2.4.8-p1
- PHP
- Composer
- Docker
- docker-magento
- WSL2
- Ubuntu 22.04
- MySQL
- OpenSearch
- Redis
- RabbitMQ
- Nginx

---

## 🚀 Ambiente

O projeto roda em **Docker + WSL2**, utilizando o [docker-magento](https://github.com/markshust/docker-magento).

```text
Projeto:    ~/Sites/magento
Storefront: https://magento.test
Admin:      https://magento.test/admin/
Modo:       developer
```

### Comandos principais

```bash
bin/start
bin/stop
bin/restart
bin/magento setup:upgrade
bin/magento cache:clean
bin/magento indexer:reindex
```

---

# 📚 Sprint 6

## ✅ 12.1 — Ambiente Magento

Configuração e validação do ambiente local Magento.

Principais entregas:

- Magento Open Source 2.4.8-p1;
- storefront e Admin;
- usuário administrativo próprio;
- 2FA;
- modo `developer`;
- MySQL, Redis, RabbitMQ e OpenSearch;
- HTTPS local confiável.

📄 [Documentação do 12.1](docs/12.1-magento-environment.md)

---

## ✅ 12.2 — Loja e Catálogo

Exploração do Magento Admin utilizando a identidade fictícia **Oak & Barrel**.

Foram criados:

- categoria `Whiskies`;
- produto `Highland Reserve 12 Years`;
- página CMS;
- CMS Block;
- alteração de configuração do catálogo.

Também foram estudados os conceitos de **Website, Store e Store View**.

📄 [Documentação do 12.2](docs/12.2-store-catalog.md)  
📄 [Website, Store e Store View](docs/12.2-website-store-store-view.md)

---

## ✅ 13.1 — Primeiro Módulo

Foi criado o módulo:

```text
Webjump_Emiliano
```

Principais recursos:

- `registration.php`;
- `module.xml`;
- Layout XML;
- ViewModel;
- template `.phtml`;
- LESS próprio;;
- escape de saídas dinâmicas;
- bloco responsivo na Home.

Fluxo:

```text
Layout XML
    ↓
ViewModel
    ↓
Template
    ↓
HTML
```

📄 [Documentação do 13.1](docs/13.1-first-module.md)

---

## ✅ 13.2 — Extensão do Catálogo

O módulo `Webjump_Emiliano` foi evoluído utilizando mecanismos oficiais de extensão do Magento.

Foram implementados:

- Plugin `afterGetName()`;
- `di.xml`;
- Observer;
- `events.xml`;
- evento `catalog_product_save_after`;
- Dependency Injection com `LoggerInterface`;
- registro no `system.log`.

O produto:

```text
Highland Reserve 12 Years
```

passa a ser exibido como:

```text
Highland Reserve 12 Years [Oak & Barrel]
```

### 🎨 Refinamento visual

Como melhoria adicional, a categoria `Whiskies` recebeu uma identidade visual alinhada à **Oak & Barrel**, com:

- cabeçalho próprio;
- toolbar;
- sidebar;
- card de produto;
- Less compartilhado;
- responsividade desktop e mobile.

📄 [Documentação do 13.2](docs/13.2-catalog-extension.md)

---

## ✅ 13.3 — Configuração no Admin

O módulo `Webjump_Emiliano` foi novamente evoluído para permitir que o conteúdo do bloco da Home seja alterado diretamente pelo Magento Admin.

Foi criada uma configuração em:

```text
Stores
→ Configuration
→ General
→ Oak & Barrel
→ Home Block
→ Message
```

Foram implementados:

- `system.xml`;
- `config.xml`;
- valor padrão;
- leitura da configuração pelo ViewModel;
- `ScopeConfigInterface`;
- Dependency Injection;
- fallback para campo vazio.

O fluxo ficou:

```text
Stores → Configuration
        ↓
ScopeConfigInterface
        ↓
ViewModel
        ↓
Template
        ↓
Home
```

A alteração feita no Admin foi validada na storefront e, caso o campo seja salvo vazio, o componente utiliza a mensagem padrão sem quebrar.

📄 [Documentação do 13.3](docs/13.3-admin-configuration.md)

---

## ✅ Comparativo de Plataformas

Como entrega final da Sprint 6, foi elaborado um breve comparativo entre **Shopify, AEM e Magento**, destacando semelhanças e diferenças observadas durante as atividades.

📄 [Shopify, AEM e Magento — Comparativo](docs/platform-comparison.md)

---

# 🚀 Sprint 7

## ✅ 14.1 — Atributo de Produto por Código

Foi criado o módulo:

```text
Webjump_ProductReviews
```

como base para as atividades da Sprint 7.

O primeiro desafio adiciona ao catálogo o atributo:

```text
Selo Sustentável
```

Principais recursos:

- atributo `sustainable_seal`;
- criação por Data Patch;
- `EavSetupFactory`;
- escopo global;
- registro em `patch_list`;
- campo booleano disponível no Admin;
- exibição condicional na página do produto;
- template `.phtml`;
- Less próprio;
- tratamento para produtos sem o selo marcado.

O escopo `GLOBAL` foi escolhido porque o atributo representa uma característica do produto/SKU, e não uma informação específica de Website ou Store View.

Diferenças de estoque, quantidade ou lote pertencem a outras responsabilidades do domínio e não alteram necessariamente a identidade do produto. Caso existam versões realmente diferentes, com características de sustentabilidade distintas, a modelagem mais adequada seria tratá-las como produtos/SKUs diferentes ou utilizar uma entidade específica para essa variação.

Fluxo:

```text
setup:upgrade
      ↓
Data Patch
      ↓
Atributo EAV
      ↓
Produto
      ↓
Layout XML
      ↓
Template
      ↓
Selo na PDP
```

📄 [Documentação do 14.1](docs/14.1-product-attribute.md)

---

## ✅ 14.2 — Entidade Própria e Repository

O módulo `Webjump_ProductReviews` foi evoluído com uma entidade própria para armazenar avaliações de produtos.

Foi criada a tabela:

```text
webjump_product_review
```

com:

```text
review_id
product_id
author
comment
rating
approved
created_at
```

Principais recursos:

- Declarative Schema;
- `db_schema_whitelist.json`;
- Model;
- ResourceModel;
- Collection;
- Service Contracts;
- Repository;
- Dependency Injection;
- `SearchCriteria`;
- paginação e limite;
- Data Patch com 5 avaliações de exemplo.

A utilização de uma **tabela própria** foi escolhida porque avaliações representam vários registros independentes relacionados a um produto, e não uma característica única que deveria ser armazenada como atributo EAV.

Fluxo:

```text
RepositoryInterface
        ↓
Repository
        ↓
Model / ResourceModel / Collection
        ↓
webjump_product_review
```

O Repository implementa:

```text
save()
getById()
delete()
getList()
```

O `getList()` utiliza `SearchCriteriaInterface`, permitindo filtros e paginação, com limite padrão para evitar consultas ilimitadas em uma tabela que pode crescer.

As `preference` configuradas em `di.xml` relacionam apenas interfaces e implementações do próprio módulo, sem substituir classes do núcleo do Magento.

📄 [Documentação do 14.2](docs/14.2-product-reviews-entity.md)

---

## ✅ 15.1 — Grid Administrativo

O módulo `Webjump_ProductReviews` foi evoluído com uma interface administrativa para consulta e gerenciamento das avaliações cadastradas.

Foi criada uma nova área no Admin:

```text
Product Reviews
└── Reviews
```

Principais recursos:

- rota administrativa;
- item de menu;
- ACL;
- Controller protegido por `ADMIN_RESOURCE`;
- UI Component Listing;
- Grid Collection;
- filtros;
- ordenação;
- paginação;
- seleção de registros;
- ação em massa para aprovação.

O grid utiliza a tabela:

```text
webjump_product_review
```

e apresenta:

```text
ID
Product ID
Author
Comment
Rating
Approved
Created At
```

Foram configurados filtros de:

```text
texto
→ Author
→ Comment

faixa numérica
→ ID
→ Product ID
→ Rating
→ Approved

data
→ Created At
```

A ação em massa:

```text
Approve
```

permite selecionar avaliações e atualizar:

```text
approved = 1
```

através do próprio Repository.

As permissões administrativas foram separadas em:

```text
Webjump_ProductReviews::reviews
Webjump_ProductReviews::reviews_export
```

Também foi realizado um teste com usuário restrito. O acesso direto ao grid foi bloqueado corretamente pelo ACL.

Fluxo:

```text
Menu
  ↓
Controller
  ↓
Layout
  ↓
UI Component
  ↓
DataSource
  ↓
Grid Collection
  ↓
webjump_product_review
```

📄 [Documentação do 15.1](docs/15.1-admin-grid.md)

---

## ✅ 15.2 — Formulário, Configuração e Exportação

O módulo `Webjump_ProductReviews` foi finalizado com um CRUD administrativo completo, configuração no Admin e exportação dos registros.

Principais recursos:

- formulário de criação e edição com UI Components;
- `DataProvider` próprio;
- validação no frontend e backend;
- criação, edição e exclusão de avaliações;
- persistência através do `ProductReviewRepository`;
- botões `Back`, `Save Review` e `Delete Review`;
- configuração em `Stores → Configuration`;
- valores padrão definidos em `config.xml`;
- controle de exibição no storefront;
- limite configurável de avaliações;
- exportação CSV;
- exportação Excel XML;
- exportação respeitando os filtros do grid.

A configuração foi adicionada em:

```text
Stores
→ Configuration
→ Catalog
→ Product Reviews
```

com:

```text
Enabled
Reviews Limit
```

O valor `Enabled` controla a exibição das avaliações customizadas na página do produto, enquanto `Reviews Limit` define a quantidade máxima de registros apresentados.

O fluxo de exibição no storefront ficou:

```text
Stores → Configuration
        ↓
Model/Config
        ↓
ProductReviews ViewModel
        ↓
Repository
        ↓
reviews.phtml
        ↓
PDP
```

Somente avaliações:

```text
approved = 1
```

relacionadas ao produto atual são exibidas.

A exportação foi integrada diretamente ao grid administrativo nos formatos:

```text
CSV
Excel XML
```

utilizando:

```text
Magento\Ui\Model\Export\ConvertToCsv
Magento\Ui\Model\Export\ConvertToXml
```

A permissão de exportação permanece separada:

```text
Webjump_ProductReviews::reviews_export
```

Foi validado que os arquivos exportados respeitam os filtros aplicados no grid. Com `Rating = 5`, apenas as avaliações de Ana Souza e Mariana Alves foram exportadas.

Nenhuma `preference` foi criada sobre classes do núcleo do Magento.

📄 [Documentação do 15.2](docs/15.2-form-config-export.md)

---

# 📁 Documentação

```text
docs/
├── 12.1-magento-environment.md
├── 12.2-store-catalog.md
├── 12.2-website-store-store-view.md
├── 13.1-first-module.md
├── 13.2-catalog-extension.md
├── 13.3-admin-configuration.md
├── platform-comparison.md
├── 14.1-product-attribute.md
├── 14.2-product-reviews-entity.md
└── images/
    ├── 12.1/
    ├── 12.2/
    ├── 13.1/
    ├── 13.2/
    ├── 13.3/
    ├── 14.1/
    └── 14.2/
```

O `README.md` apresenta uma visão geral das entregas, enquanto os arquivos em `docs/` concentram as decisões técnicas, validações e evidências.

---

# 🔒 Boas Práticas

- código customizado em `src/app/code/`;
- nenhum arquivo em `vendor/` alterado;
- credenciais e chaves não versionadas;
- lógica separada da apresentação;
- saídas dinâmicas escapadas;
- uso de mecanismos oficiais de extensão do Magento;
- Dependency Injection;
- configuração administrável pelo Magento;
- branches por exercício;
- commits semânticos;
- Pull Requests revisados;
- alterações de banco e atributos reproduzíveis por código;
- Data Patches para mudanças de dados;
- Declarative Schema para estrutura de banco;
- Service Contracts para acesso às entidades;
- decisões técnicas justificadas pelo cenário;
- nenhuma `preference` substituindo classe do núcleo;
- consultas com paginação e limite quando houver possibilidade de crescimento;
- validação final em base limpa.

---

# 📌 Status

```text
Sprint 6

├── 12.1 — Ambiente Magento                ✅
├── 12.2 — Loja e catálogo                 ✅
├── 13.1 — Primeiro módulo                 ✅
├── 13.2 — Extensão do catálogo            ✅
├── 13.3 — Configuração no Admin           ✅
└── Shopify / AEM / Magento — Comparativo  ✅


Sprint 7

├── 14.1 — Atributo de produto por código  ✅
├── 14.2 — Entidade e Repository           ✅
├── 15.1 — Grid administrativo             ⏳
└── 15.2 — Formulário e exportação         ⏳
```