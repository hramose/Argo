if grep 'Arch Linux' /etc/issue > /dev/null; then
	sudo pacman -S apache php php-apache yaourt php-cli php-curl php-imap php-json php-mcrypt php-pgsql php-sqlite php-xdebug
	yaourt -S a2enmod-git
else 
	sudo apt-get install php5 php5-cli php5-curl php5-imap php5-json php5-mcrypt php5-pgsql php5-sqlite php5-xdebug 	
fi;
if [ -f /usr/bin/composer ] || [ -f /usr/local/bin/composer ]; then
	echo "Actualizando dependencias."
	composer install
else
	if [ -f composer.phar ]; then
		echo "Actualizando dependencias."
		./composer.phar install
	else
		if [ -f /usr/bin/curl ]; then
			echo "Composer no encontrado, descargando composer"
			curl -sS https://getcomposer.org/installer | php
		else 
			echo "¿Al parecer CURL no está instalado, desea instalarlo? [S/N]";
			read response
			response=$(echo $response | tr 'a-z' 'A-Z')
			if [ $response == "S" ] || [ $response == "SI" ]; then 
				echo "Descargando CURL"
				sudo apt-get install curl	
				echo "Composer no encontrado, descargando composer"
				curl -sS https://getcomposer.org/installer | php
			else
				echo "Se instalará composer a través de php..."
				php -r "readfile('https://getcomposer.org/installer');" | php
			fi
		fi
		echo "Actualizando dependencias."
		./composer.phar install
		echo "¿Desea instalar Composer permanentemente en su sistema? [S/N]";
		read response
		response=$(echo $response | tr 'a-z' 'A-Z')
		if [ $response == "S" ] || [ $response == "SI" ]; then 
			echo "Instalando composer..."
			sudo mv composer.phar /usr/local/bin/composer
		else
			rm composer.phar
		fi
	fi
fi
echo "Activando módulos de php"
sudo php5enmod mcrypt
sudo a2enmod rewrite
echo "Configurando permisos de directorios"
sudo chmod 777 -R vendor/
sudo chmod 777 -R storage/
sudo chmod 777 -R bootstrap/cache/
php artisan key:generate #Crea la app key de Laravel.
php artisan jwt:generate #Crea la llave del JWT AUTH
if grep 'Arch Linux' /etc/issue > /dev/null; then
	sudo systemctl restart httpd
else
	sudo service apache2 restart
fi;
