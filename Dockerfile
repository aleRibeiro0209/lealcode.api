# Use uma imagem base com PHP 8.3 e Apache
FROM php:8.3-apache

# Instalar extensões necessárias (curl, pdo_mysql)
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl pdo pdo_mysql

# Aumentar a memória do PHP para ilimitada
RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/memory-limit.ini

# Instalar o Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copiar os arquivos da aplicação para o diretório web
COPY . /var/www/html/

# Executar o Composer para instalar as dependências
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expor a porta 80 para o servidor Apache
EXPOSE 80

# Iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
