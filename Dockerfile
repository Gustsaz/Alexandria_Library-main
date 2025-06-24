# Usar imagem oficial PHP com Apache
FROM php:8.2-apache

# Copiar todo o conteúdo da pasta atual para o diretório padrão do Apache no container
COPY . /var/www/html/

RUN chmod -R 755 /var/www/html/

# Ajustar permissões para os arquivos (opcional, mas recomendado)
RUN chown -R www-data:www-data /var/www/html/

RUN echo "Conteúdo da pasta Icons:" && ls -la /var/www/html/Icons/

# Habilitar mod_rewrite do Apache (muito comum para projetos PHP)
RUN a2enmod rewrite

# Expor a porta 80 (HTTP)
EXPOSE 80

# Comando padrão para iniciar o Apache em primeiro plano (já configurado na imagem oficial)
CMD ["apache2-foreground"]
