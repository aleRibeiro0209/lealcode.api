# Use uma imagem base com PHP 8.3 e Apache
FROM php:8.3-apache

# Instalar extensões necessárias (curl, pdo_mysql) e utilitários
# Etapa 1: Instalar dependências gerais
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

# Etapa 3: Instalar o curl, pdo e pdo_mysql
RUN docker-php-ext-install curl pdo pdo_mysql

# Etapa 2: Configurar e instalar a extensão GD separadamente
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-png \
    && docker-php-ext-install gd

# Limpar o cache do apt para reduzir o tamanho da imagem
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Aumentar a memória do PHP para ilimitada
RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/memory-limit.ini

# Instalar o Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos da aplicação para o diretório web
COPY . /var/www/html/

# Ajustar o DocumentRoot para apontar para o diretório "public" do Laravel (se aplicável)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Instalar as dependências do Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Definir ServerName para evitar o aviso do Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Habilitar o mod_rewrite e configurar o AllowOverride para suportar .htaccess
RUN a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expor a porta 80 para o servidor Apache
EXPOSE 80

# Iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
