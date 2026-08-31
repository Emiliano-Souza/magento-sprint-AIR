# Magento Sprint AIR

Projeto desenvolvido durante a Sprint 6 do Programa de Estágio da Webjump, com foco em Magento 2 / Adobe Commerce.

O objetivo da sprint é configurar um ambiente local de desenvolvimento, explorar o catálogo e o CMS do Magento e desenvolver módulos utilizando os mecanismos oficiais de extensão da plataforma.

## Tecnologias

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

## Ambiente local

O projeto utiliza o [docker-magento](https://github.com/markshust/docker-magento) para executar os serviços necessários ao Magento em containers Docker.

No Windows, o projeto é executado dentro do WSL2.

### Diretório do projeto

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

## Comandos básicos do ambiente

### Iniciar

```bash
bin/start
```

### Parar

```bash
bin/stop
```

### Reiniciar

```bash
bin/restart
```

### Verificar containers

```bash
docker ps
```

## Comandos Magento mais utilizados

### Atualizar a estrutura do Magento

```bash
bin/magento setup:upgrade
```

Utilizado principalmente após alterações estruturais em módulos.

### Limpar o cache

```bash
bin/magento cache:clean
```

Utilizado quando uma alteração foi realizada, mas ainda não apareceu na aplicação.

### Reconstruir os índices

```bash
bin/magento indexer:reindex
```

Reconstrói os índices utilizados pelo Magento para otimizar consultas de catálogo, produtos, preços e outras informações.

### Verificar o modo da aplicação

```bash
bin/magento deploy:mode:show
```

O ambiente local está configurado no modo:

```text
developer
```

---

# Desafios

## 12.1 — Ambiente Magento no ar

### Objetivo

Configurar o Magento Open Source localmente utilizando Docker e WSL2, garantindo acesso à storefront e ao painel administrativo.

### Resultado

- Magento Open Source 2.4.8-p1 instalado
- Storefront funcionando
- Admin funcionando
- Usuário administrativo próprio criado
- Modo developer ativo
- SSL local configurado
- Containers essenciais funcionando

### Preparação do Ubuntu

As dependências necessárias para o ambiente foram instaladas:

```bash
sudo apt update
sudo apt install -y curl libnss3-tools unzip rsync bc jq dos2unix
```

O Git dentro do WSL foi configurado para utilizar final de linha LF:

```bash
git config --global core.autocrlf false
git config --global core.eol lf
```

### Criação do projeto

O projeto foi criado dentro do filesystem Linux do WSL:

```bash
mkdir -p ~/Sites/magento
cd ~/Sites/magento
```

A estrutura do docker-magento foi criada com:

```bash
curl -s https://raw.githubusercontent.com/markshust/docker-magento/master/lib/template | bash
```

Para evitar problemas de quebra de linha entre Windows e Linux, os arquivos de `bin/` e `env/` foram normalizados:

```bash
find ./bin -type f -exec sed -i 's/\r$//' {} +
find ./env -type f -exec sed -i 's/\r$//' {} +
```

Foi utilizada a configuração de desenvolvimento otimizada para Linux:

```bash
cp compose.dev-linux.yaml compose.dev.yaml
```

### Download do Magento

O Magento Open Source 2.4.8-p1 foi baixado utilizando:

```bash
bin/download community 2.4.8-p1
```

A autenticação do Composer foi realizada utilizando as Access Keys do Magento Marketplace.

Nenhuma credencial utilizada durante a instalação é versionada no repositório.

### Configuração do domínio local

Foi adicionada a seguinte entrada no arquivo `hosts` do WSL:

```text
127.0.0.1 magento.test
```

A mesma entrada também foi adicionada ao arquivo `hosts` do Windows:

```text
C:\Windows\System32\drivers\etc\hosts
```

### Instalação

A instalação foi executada com:

```bash
bin/setup magento.test
```

Após a instalação, os ambientes ficaram disponíveis em:

**Storefront**

```text
https://magento.test
```

**Admin**

```text
https://magento.test/admin/
```

### Usuário administrativo próprio

Foi criado um usuário administrativo próprio utilizando:

```bash
bin/magento admin:user:create
```

O usuário foi validado no painel administrativo e a autenticação em dois fatores foi configurada com sucesso.

### Modo developer

O modo da aplicação foi validado com:

```bash
bin/magento deploy:mode:show
```

Resultado:

```text
Current application mode: developer
```

### Comandos validados

Durante a configuração do ambiente, os seguintes comandos foram executados e validados:

```bash
bin/magento setup:upgrade
bin/magento cache:clean
bin/magento indexer:reindex
```

#### `setup:upgrade`

Atualiza a estrutura e as configurações do Magento após alterações relacionadas a módulos.

#### `cache:clean`

Limpa os caches do Magento quando uma alteração realizada ainda não está sendo refletida na aplicação.

#### `indexer:reindex`

Reconstrói os índices utilizados pelo Magento para otimizar consultas relacionadas ao catálogo, produtos, preços e outras informações.

## Problemas encontrados durante o setup

### 1. Configuração inválida do ProFTPD

Durante a preparação do Ubuntu, o pacote `proftpd-core` apresentava erro de configuração e impedia o `dpkg` de finalizar corretamente.

Foram encontradas diretivas inválidas no arquivo:

```text
/etc/proftpd/proftpd.conf
```

Entre os problemas encontrados estavam configurações como:

```text
DefaultRoot~
```

e:

```text
RequireValidShell~
```

As diretivas foram corrigidas para valores válidos.

Depois disso, a configuração do ProFTPD foi validada e o sistema de pacotes foi reparado com:

```bash
sudo dpkg --configure -a
sudo apt --fix-broken install
```

Após a correção, o `apt` voltou a funcionar normalmente.

### 2. Memória insuficiente para o Docker

Durante o download do Magento, o docker-magento informou que era necessário disponibilizar pelo menos 6 GB de memória para o Docker.

O WSL estava limitado a aproximadamente 4 GB.

O arquivo:

```text
C:\Users\<usuario>\.wslconfig
```

foi alterado para disponibilizar 8 GB ao WSL:

```ini
[wsl2]
memory=8GB
swap=2GB
```

Após a alteração, o WSL foi encerrado com:

```powershell
wsl --shutdown
```

e iniciado novamente.

### 3. Conflito na porta 3306

Durante a inicialização dos containers, o banco de dados do Magento não conseguia iniciar porque a porta `3306` já estava sendo utilizada no Windows.

Foi identificado um serviço MySQL existente:

```text
MySQL80
```

O serviço foi parado com:

```powershell
Stop-Service -Name MySQL80
```

Após liberar a porta `3306`, o container do banco do Magento conseguiu iniciar normalmente.

### 4. OpenSearch unhealthy

Durante o setup, o container do OpenSearch era marcado como `unhealthy` antes de terminar completamente sua inicialização.

A configuração do sistema foi verificada com:

```bash
sysctl vm.max_map_count
```

Resultado:

```text
vm.max_map_count = 262144
```

Os logs também mostraram que o OpenSearch conseguia iniciar e que o cluster posteriormente atingia:

```text
GREEN
```

O problema estava relacionado ao tempo permitido pelo healthcheck.

O arquivo:

```text
compose.healthcheck.yaml
```

foi ajustado para aumentar a quantidade de tentativas do healthcheck do OpenSearch:

```yaml
retries: 30
```

Após o ajuste, o container passou a atingir o estado:

```text
healthy
```

e o setup pôde continuar normalmente.

### 5. Certificado SSL não confiável no Windows

Após executar:

```bash
bin/setup-ssl magento.test
```

o HTTPS funcionava, porém o Chrome ainda apresentava o site como não confiável.

Foi identificado que existiam duas autoridades certificadoras diferentes:

- uma CA criada pelo `mkcert` do WSL;
- uma CA utilizada internamente pelo ambiente docker-magento.

O certificado de `magento.test` havia sido assinado pela CA utilizada dentro do container.

A CA correta foi copiada do container para o projeto:

```bash
docker cp "$(bin/docker-compose ps -q app):/root/.local/share/mkcert/rootCA.pem" ./docker-magento-rootCA.pem
```

A autoridade certificadora foi validada com:

```bash
openssl x509 -in docker-magento-rootCA.pem -noout -subject
```

Depois, o certificado foi importado no repositório de autoridades certificadoras confiáveis do Windows.

Após reiniciar completamente o Chrome, o navegador passou a reconhecer:

```text
https://magento.test
```

como uma conexão confiável.

> O certificado utilizado apenas para configuração local não é versionado no repositório.

## Validação final do ambiente

Ao final do desafio, foram validados:

- Docker funcionando
- WSL2 funcionando
- Magento rodando dentro do filesystem Linux
- Storefront acessível
- Admin acessível
- Usuário administrativo próprio funcionando
- 2FA configurado
- Modo developer ativo
- MySQL funcionando em container
- Redis funcionando
- RabbitMQ funcionando
- OpenSearch funcionando
- SSL local confiável
- Cache funcionando
- Indexadores funcionando

---

## 12.2 — Explorando a loja e o catálogo

Em desenvolvimento.

---

## 13.1 — Primeiro módulo com bloco na home

Em desenvolvimento.

---

## 13.2 — Estendendo o comportamento do catálogo

Em desenvolvimento.

---

# Documentação

O `README.md` é utilizado como documentação principal do projeto.

Caso seja necessário adicionar documentação mais extensa durante o desenvolvimento, será utilizada a pasta:

```text
docs/
```

A estrutura poderá ser organizada conforme a necessidade:

```text
docs/
├── environment/
├── catalog/
├── modules/
└── troubleshooting/
```

A criação dessas pastas será feita somente quando houver documentação suficiente para justificar a separação do conteúdo.

# Segurança

Os seguintes tipos de arquivos não devem ser versionados:

- Access Keys do Magento Marketplace
- credenciais do Composer
- `auth.json`
- certificados privados
- chaves privadas
- arquivos específicos do ambiente local
- arquivos temporários de troubleshooting

Credenciais e outros dados sensíveis devem permanecer somente no ambiente local.
