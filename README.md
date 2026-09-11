# Magento Sprint AIR

Projeto desenvolvido durante a **Sprint 6 do Programa de Estágio da Webjump**, com foco em Magento 2 / Adobe Commerce.

Durante a sprint foram trabalhados configuração de ambiente, catálogo, CMS e desenvolvimento de módulos utilizando os mecanismos oficiais de extensão do Magento.

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

## 🚀 Ambiente local

O projeto utiliza o [docker-magento](https://github.com/markshust/docker-magento) para executar os serviços necessários ao Magento em containers Docker.

No Windows, o projeto é executado dentro do **WSL2**, com os arquivos mantidos no filesystem Linux.

### Diretório

```text
~/Sites/magento
```

### Storefront

```text
https://magento.test
```

### Admin

```text
https://magento.test/admin/
```

### Modo

```text
developer
```

---

## ⚙️ Comandos principais

### Iniciar o ambiente

```bash
bin/start
```

### Parar o ambiente

```bash
bin/stop
```

### Reiniciar

```bash
bin/restart
```

### Atualizar estrutura dos módulos

```bash
bin/magento setup:upgrade
```

### Limpar cache

```bash
bin/magento cache:clean
```

### Reconstruir índices

```bash
bin/magento indexer:reindex
```

### Verificar o modo da aplicação

```bash
bin/magento deploy:mode:show
```

---

# 📚 Desafios

## ✅ 12.1 — Ambiente Magento no ar

O primeiro desafio teve como objetivo preparar o ambiente local completo utilizando **Docker + WSL2**.

Foram configurados e validados:

- Magento Open Source 2.4.8-p1;
- storefront;
- painel administrativo;
- usuário administrativo próprio;
- autenticação em dois fatores;
- modo `developer`;
- MySQL;
- Redis;
- RabbitMQ;
- OpenSearch;
- HTTPS local confiável.

Durante o setup também foram resolvidos problemas relacionados a:

- ProFTPD;
- memória do WSL;
- conflito da porta `3306`;
- healthcheck do OpenSearch;
- certificado SSL local.

📄 [Documentação completa do exercício 12.1](docs/12.1-magento-environment.md)

---

## ✅ 12.2 — Explorando a loja e o catálogo

O desafio 12.2 teve como objetivo explorar o Magento Admin e entender o funcionamento do catálogo, CMS e configurações da loja.

Foi utilizada a identidade fictícia **Oak & Barrel**, uma loja especializada em whiskies.

Foram criados:

- categoria `Whiskies`;
- produto simples `Highland Reserve 12 Years`;
- associação do produto à categoria;
- página CMS `About Oak & Barrel`;
- CMS Block `Oak & Barrel Special Selection`;
- exibição do CMS Block na storefront.

Também foi alterada uma configuração em:

```text
Stores → Configuration → Catalog → Catalog → Storefront
```

O `List Mode` passou de:

```text
Grid (default) / List
```

para:

```text
List (default) / Grid
```

e o efeito foi validado diretamente na storefront.

📄 [Documentação completa do exercício 12.2](docs/12.2-store-catalog.md)

📄 [Website, Store e Store View](docs/12.2-website-store-store-view.md)

---

## ✅ 13.1 — Primeiro módulo com bloco na Home

No desafio 13.1 foi criado do zero o módulo:

```text
Webjump_Emiliano
```

O módulo adiciona um bloco próprio na página inicial da loja, mantendo a lógica separada da camada de apresentação.

### Estrutura

```text
src/app/code/Webjump/Emiliano/
├── registration.php
├── etc/
│   └── module.xml
├── ViewModel/
│   └── HomeMessage.php
└── view/
    └── frontend/
        ├── layout/
        │   └── cms_index_index.xml
        ├── templates/
        │   └── home-message.phtml
        └── web/
            └── css/
                └── home-message.css
```

O fluxo utilizado é:

```text
Layout XML
↓
ViewModel
↓
Template .phtml
↓
HTML
```

### Responsabilidades

- `registration.php` registra o módulo no Magento;
- `module.xml` declara o módulo;
- `cms_index_index.xml` adiciona o bloco à Home;
- `HomeMessage.php` fornece os dados através de um ViewModel;
- `home-message.phtml` é responsável apenas pela apresentação;
- `home-message.css` contém o estilo próprio do componente.

As saídas dinâmicas são tratadas com:

```text
escapeHtml()
escapeUrl()
```

O bloco recebeu identidade visual baseada na **Oak & Barrel** e também possui adaptação responsiva para dispositivos móveis.

O módulo foi validado com:

```bash
bin/magento module:status Webjump_Emiliano
```

e a sintaxe dos arquivos PHP foi verificada dentro do container.

📄 [Documentação completa do exercício 13.1](docs/13.1-first-module.md)

---

## ✅ 13.2 — Estendendo o comportamento do catálogo

No desafio 13.2, o módulo `Webjump_Emiliano` foi evoluído utilizando mecanismos oficiais de extensão do Magento.

Foram implementados:

- Plugin do tipo `after`;
- declaração do Plugin em `di.xml`;
- Observer;
- declaração do Observer em `events.xml`;
- evento `catalog_product_save_after`;
- Dependency Injection com `LoggerInterface`;
- registro de informações no log do Magento.

### Plugin

Foi criado:

```text
Plugin/ProductNamePlugin.php
```

O Plugin intercepta:

```text
Magento\Catalog\Model\Product::getName()
```

através de:

```text
afterGetName()
```

O nome:

```text
Highland Reserve 12 Years
```

passa a ser exibido como:

```text
Highland Reserve 12 Years [Oak & Barrel]
```

sem alteração da classe original do Magento.

### Observer

Foi criado:

```text
Observer/ProductSaveObserver.php
```

O Observer reage ao evento:

```text
catalog_product_save_after
```

e registra no `system.log` informações como:

```text
product_id
sku
name
```

A execução foi validada após salvar o produto através do Magento Admin.

### Plugin x Observer

Neste exercício:

```text
Plugin
→ intercepta um método
→ modifica seu resultado

Observer
→ escuta um evento
→ reage quando esse evento acontece
```

Isso permitiu praticar dois mecanismos diferentes de extensão sem modificar arquivos do núcleo da plataforma.

### Refinamento visual da categoria Whiskies

Como melhoria adicional, a categoria `Whiskies` recebeu uma identidade visual alinhada à Oak & Barrel.

Foram adicionados:

```text
view/frontend/
├── layout/
│   ├── default.xml
│   ├── cms_index_index.xml
│   └── catalog_category_view.xml
└── web/
    └── css/
        ├── oak-barrel-base.css
        ├── home-message.css
        └── whisky-category.css
```

O arquivo:

```text
oak-barrel-base.css
```

centraliza variáveis compartilhadas da identidade visual, permitindo reutilização entre a Home e a categoria.

A página `Whiskies` recebeu ajustes em:

- cabeçalho;
- toolbar;
- sidebar;
- card do produto;
- imagem;
- preço;
- CTA;
- links;
- espaçamento;
- responsividade.

Os estilos específicos foram limitados através de:

```css
body.category-whiskies
```

evitando alterações nas demais categorias da loja.

A página também foi validada em desktop e mobile.

📄 [Documentação completa do exercício 13.2](docs/13.2-catalog-extension.md)

---

# 📁 Documentação

A documentação detalhada de cada exercício está organizada em:

```text
docs/
├── 12.1-magento-environment.md
├── 12.2-store-catalog.md
├── 12.2-website-store-store-view.md
├── 13.1-first-module.md
├── 13.2-catalog-extension.md
└── images/
    ├── 12.1/
    ├── 12.2/
    ├── 13.1/
    └── 13.2/
```

O `README.md` funciona como ponto de entrada do projeto, enquanto os arquivos em `docs/` concentram procedimentos, explicações, troubleshooting e evidências de cada desafio.

---

# 🔒 Segurança e boas práticas

Durante o projeto:

- nenhum arquivo dentro de `vendor/` é alterado;
- o código customizado fica em `src/app/code/`;
- Access Keys do Magento não são versionadas;
- credenciais do Composer não são versionadas;
- `auth.json` permanece fora do Git;
- certificados e chaves privadas permanecem locais;
- arquivos gerados pelo Magento não são versionados;
- a lógica de apresentação fica em ViewModels;
- templates `.phtml` mantêm somente responsabilidades de apresentação;
- toda saída dinâmica é escapada;
- Plugins e Observers são utilizados para estender comportamentos sem alterar o núcleo;
- dependências são recebidas através de Dependency Injection;
- cada exercício é desenvolvido em branch própria;
- são utilizados commits semânticos e Pull Requests revisados.

---

# 📌 Status da Sprint

```text
Sprint 6
├── 12.1 — Ambiente Magento                ✅
├── 12.2 — Loja e catálogo                 ✅
├── 13.1 — Primeiro módulo                 ✅
├── 13.2 — Extensão do catálogo            ✅
├── 13.3 — Configuração no Admin           ⏳
└── Shopify / AEM / Magento — Comparativo  ⏳
```