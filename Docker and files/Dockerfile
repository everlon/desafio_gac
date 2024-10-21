# Usando a imagem oficial PHP 8.2 com Apache
FROM php:8.2-apache

# Instalar as extensões PHP necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo_mysql

# Configuração do Apache para apontar para o diretório public do Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Copiar a configuração do Apache personalizada
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Habilitar o módulo de reescrita do Apache (necessário para o Laravel)
RUN a2enmod rewrite

# Definir o diretório de trabalho para a pasta example-app
WORKDIR /var/www/html

# Copiar os arquivos do projeto para o container
COPY example-app /var/www/html

# Alterar permissões dos arquivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expor a porta 80
EXPOSE 80

# Comando padrão para iniciar o Apache
CMD ["apache2-foreground"]
