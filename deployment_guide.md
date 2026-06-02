# Guia de Deploy em VPS HostGator 🚀

Este guia detalha o passo a passo para implantar o seu sistema de gerador de orçamentos no VPS da HostGator. Como a HostGator oferece VPSs com **cPanel** (painel gráfico) ou **limpos** (acesso puramente via terminal SSH), dividimos o guia nos dois cenários mais comuns.

---

## 📌 Cenário A: VPS com cPanel (Recomendado/Mais Comum)

Se o seu plano da HostGator inclui o cPanel, você pode fazer grande parte da configuração visualmente.

### 1. Preparar o Domínio e Document Root
O Laravel exige que a pasta pública visível na web seja a pasta `/public`, e **não** a raiz do projeto.
1. No cPanel, acesse **Domínios**.
2. Altere o diretório raiz (*Document Root*) do seu domínio para apontar para `public_html/gerador/public` ou diretamente `public_html/public` se for o domínio principal.
3. Se a HostGator não permitir alterar o Document Root do domínio principal (que por padrão é travado em `public_html`), siga esta alternativa:
   * Coloque os arquivos do Laravel uma pasta acima de `public_html` (na raiz da sua conta SSH).
   * Mova apenas os arquivos de dentro de `public` para a pasta `public_html`.
   * Edite o arquivo `public_html/index.php` e corrija os caminhos do autoload e bootstrap para apontar para a pasta acima (`../geradororcamentoslaravel/...`).

### 2. Criar o Banco de Dados
1. No cPanel, vá em **Bancos de Dados MySQL** (ou *Assistente de Banco de Dados MySQL*).
2. Crie um novo banco (ex: `usuario_orcamentos`).
3. Crie um novo usuário de banco de dados e defina uma senha forte.
4. Associe o usuário ao banco de dados e marque a opção **Todos os Privilégios**.

### 3. Enviar o Código via Git Version Control
1. No cPanel, acesse **Git™ Version Control**.
2. Clique em **Criar** e configure a URL do seu repositório Git (ex: do GitHub).
3. Defina o caminho onde o projeto será clonado no servidor.
4. Use o botão **Pull** sempre que quiser atualizar o código com suas alterações locais.

### 4. Configurar Dependências e .env
1. Acesse o **Gerenciador de Arquivos** do cPanel.
2. Mostre os arquivos ocultos (clique em Configurações no canto superior direito e marque "Mostrar arquivos ocultos").
3. Duplique o arquivo `.env.example` e renomeie-o para `.env`.
4. Edite o `.env` e preencha as variáveis de produção (Banco de Dados, Chaves Stripe de Produção, SMTP de e-mail real, etc.).
5. Defina `APP_ENV=production` e `APP_DEBUG=false`.
6. Abra o terminal SSH ou a ferramenta **Terminal** no cPanel e rode:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   ```

---

## 📌 Cenário B: VPS Limpo (Acesso via Terminal SSH - Ubuntu)

Se você escolheu um VPS limpo sem painel gráfico, faremos toda a instalação da stack LEMP via SSH.

### 1. Atualizar o Servidor e Instalar PHP
Conecte no seu VPS via SSH (`ssh root@ip_do_servidor`) e execute:
```bash
sudo apt update && sudo apt upgrade -y
# Adicionar repositório do PHP se necessário
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instalar PHP 8.2 e extensões necessárias para o Laravel
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-bcmath -y
```

### 2. Instalar Nginx, MySQL e Composer
```bash
# Instalar Nginx e MySQL
sudo apt install nginx mysql-server unzip curl -y

# Instalar Composer Globalmente
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3. Configurar Banco de Dados MySQL
```bash
sudo mysql
# No prompt do MySQL:
CREATE DATABASE gerador_orcamentos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'SUA_SENHA_FORTE';
GRANT ALL PRIVILEGES ON gerador_orcamentos.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Clonar e Configurar o Projeto
1. Clone seu repositório Git em `/var/www/gerador`:
   ```bash
   cd /var/www
   git clone <URL_DO_SEU_REPOSITORIO> gerador
   cd gerador
   ```
2. Instalar dependências:
   ```bash
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   nano .env # Insira os dados de produção, Banco de Dados, Stripe e E-mail
   php artisan key:generate
   php artisan migrate --force
   ```
3. Permissões de escrita necessárias para o Laravel funcionar:
   ```bash
   sudo chown -R www-data:www-data /var/www/gerador/storage /var/www/gerador/bootstrap/cache
   sudo chmod -R 775 /var/www/gerador/storage /var/www/gerador/bootstrap/cache
   ```

### 5. Configurar o Bloco do Nginx (Virtual Host)
Crie um arquivo de configuração para o seu domínio `/etc/nginx/sites-available/seu-dominio.com`:
```nginx
server {
    listen 80;
    server_name seu-dominio.com www.seu-dominio.com;
    root /var/www/gerador/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Ative o site e reinicie o Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/seu-dominio.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 6. Instalar SSL Grátis (HTTPS)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d seu-dominio.com -d www.seu-dominio.com
```

---

## ⚡ Configurações Finais de Produção (Ambos os Cenários)

Para o sistema funcionar sem problemas, você precisará configurar duas tarefas automáticas no servidor (Cron Job e Workers de Fila).

### A. Cron Job (Agendador de Tarefas do Laravel)
O Laravel precisa de uma tarefa rodando a cada minuto no servidor para processar assinaturas expiradas do Stripe, redefinir limites mensais, etc.
* **No cPanel:** Vá em **Trabalhos Cron** (*Cron Jobs*) e adicione esta regra para rodar **a cada minuto** (`* * * * *`):
  ```bash
  /usr/local/bin/php /home/usuario/public_html/gerador/artisan schedule:run >> /dev/null 2>&1
  ```
  *(Confirme o caminho correto do binário do PHP e do arquivo artisan no seu servidor).*

* **No SSH/VPS Limpo:** Rode `crontab -e` e adicione no final do arquivo:
  ```bash
  * * * * * cd /var/www/gerador && php artisan schedule:run >> /dev/null 2>&1
  ```

### B. Filas (Queue Worker)
O envio de e-mails e comunicação com o Stripe podem atrasar o carregamento da página se forem executados de forma síncrona. Recomendamos configurar o driver de fila como `database` ou `redis`.
* Configure no seu `.env`:
  ```env
  QUEUE_CONNECTION=database
  ```
* No servidor VPS Limpo, configure o **Supervisor** para manter o comando `php artisan queue:work` sempre rodando em segundo plano.
* No cPanel, caso não tenha acesso ao Supervisor, você pode criar uma tarefa cron periódica para rodar `php artisan queue:work --once` a cada poucos minutos ou usar o driver de fila `sync` temporariamente.
