# 🛒 Magento Sprint AIR

Projeto desenvolvido durante a **Sprint 6 do Programa de Estágio da Webjump**, com foco em Magento 2 / Adobe Commerce.

A sprint aborda configuração de ambiente, catálogo, CMS e desenvolvimento de módulos utilizando os mecanismos oficiais de extensão do Magento.

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

# 📚 Desafios

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

## ✅ 13.1 — Primeiro módulo

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
- CSS próprio;
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

O módulo `Webjump_Emiliano` foi evoluído utilizando os mecanismos oficiais de extensão do Magento.

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
- CSS compartilhado;
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

Como entrega final da Sprint, foi elaborado um breve comparativo entre **Shopify, AEM e Magento**, destacando semelhanças e diferenças observadas durante as atividades.

📄 [Shopify, AEM e Magento — Comparativo](docs/platform-comparison.md)

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
└── images/
    ├── 12.1/
    ├── 12.2/
    ├── 13.1/
    ├── 13.2/
    └── 13.3/
```

O `README.md` apresenta uma visão geral da Sprint, enquanto os arquivos em `docs/` concentram as implementações e evidências.

---

# 🔒 Boas práticas

- código customizado em `src/app/code/`;
- nenhum arquivo em `vendor/` alterado;
- credenciais e chaves não versionadas;
- lógica separada da apresentação;
- saídas dinâmicas escapadas;
- uso de Plugin e Observer para extensão;
- Dependency Injection;
- configuração administrável pelo Magento;
- branches por exercício;
- commits semânticos;
- Pull Requests revisados.

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
```