# Use uma imagem base com PHP 8.3 e Apache
FROM php:8.3-apache

# Instalar extensões necessárias (curl, pdo_mysql) e utilitários
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install curl pdo pdo_mysql

# Aumentar a memória do PHP para ilimitada
RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/memory-limit.ini

# Instalar o Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos da aplicação para o diretório web
COPY . /var/www/html/

# Garantir que o usuário www-data tenha as permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Instalar as dependências do Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expor a porta 80 para o servidor Apache
EXPOSE 80

# Iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
