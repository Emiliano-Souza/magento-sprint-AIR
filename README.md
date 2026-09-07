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

## 🟡 13.2 — Estendendo o comportamento do catálogo

Próximo desafio da Sprint.

O exercício utilizará os mecanismos oficiais de extensão do Magento para modificar comportamentos sem alterar o núcleo da plataforma.

Serão implementados:

- plugin do tipo `after`;
- declaração em `di.xml`;
- observer;
- declaração em `events.xml`;
- evento do catálogo;
- registro de mensagem em log.

Nenhum arquivo dentro de `vendor/` será modificado.

---

# 📁 Documentação

A documentação detalhada de cada exercício está organizada em:

```text
docs/
├── 12.1-magento-environment.md
├── 12.2-store-catalog.md
├── 12.2-website-store-store-view.md
├── 13.1-first-module.md
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
- cada exercício é desenvolvido em branch própria;
- são utilizados commits semânticos e Pull Requests revisados.

---

# 📌 Status da Sprint

```text
Sprint 6
├── 12.1 — Ambiente Magento                ✅
├── 12.2 — Loja e catálogo                 ✅
├── 13.1 — Primeiro módulo                 ✅
├── 13.2 — Extensão do catálogo            🟡
└── Shopify / AEM / Magento — Comparativo  ⏳
```