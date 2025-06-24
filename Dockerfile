# Usar imagem oficial PHP com Apache
FROM php:8.2-apache

# Copiar todo o conteúdo da pasta atual para o diretório padrão do Apache
COPY . /var/www/html/

# Ajustar permissões
RUN chmod -R 755 /var/www/html/ && chown -R www-data:www-data /var/www/html/

# Debug: Listar os arquivos dentro da pasta Icons
RUN echo "Conteúdo da pasta Icons:" && ls -la /var/www/html/icons/

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Expor a porta 80
EXPOSE 80

# Start do Apache
CMD ["apache2-foreground"]
