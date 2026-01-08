#!/bin/bash

PROJECT_NAME="ebazar"
DEPLOY_PATH="/var/www/html/$PROJECT_NAME"
PUBLIC_PATH="$DEPLOY_PATH/app/public"
UPLOAD_PATH="$PUBLIC_PATH/uploads"
SQL_FILE="$DEPLOY_PATH/sql/script.sql"
APACHE_CONF_FILE="/etc/apache2/apache2.conf"
VHOST_CONF_FILE="/etc/apache2/sites-available/${PROJECT_NAME}.conf"
HTACCESS_FILE="$PUBLIC_PATH/.htaccess"
DEPLOY_CONFIG_PATH="$DEPLOY_PATH/app/config.php"

if [ ! -d "$DEPLOY_PATH" ]; then
    echo "Le project n'existe pas dans $DEPLOY_PATH"
    exit 1
fi

a2enmod rewrite >/dev/null 2>&1

APACHE_BLOCK="<Directory $PUBLIC_PATH>
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
    </Directory>"

if ! grep -q "$PUBLIC_PATH" "$APACHE_CONF_FILE"; then
    echo "$APACHE_BLOCK" >> "$APACHE_CONF_FILE"
fi

cat > "$VHOST_CONF_FILE" <<EOL
<VirtualHost *:80>
    ServerName 192.168.76.76
    ServerAdmin admin@localhost
    DocumentRoot $PUBLIC_PATH
    <Directory $PUBLIC_PATH>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/${PROJECT_NAME}-error.log
    CustomLog \${APACHE_LOG_DIR}/${PROJECT_NAME}-access.log combined
</VirtualHost>
EOL

a2dissite 000-default.conf >/dev/null 2>&1
a2ensite "${PROJECT_NAME}.conf" >/dev/null 2>&1

cat > "$HTACCESS_FILE" <<EOL
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
<FilesMatch "configure_project.sh">
    Require all denied
</FilesMatch>
EOL

rm -f "/var/www/html/index.html"
rm -f "/var/www/html/test.php"

ask() {
    read -p "$1 [$2]: " input
    echo "${input:-$2}"
}

db_host=$(ask "hôte" "localhost")
db_name=$(ask "base de données" "projet")
db_user=$(ask "utilisateur" "projet")
db_pass=$(ask "mot de passe" "tejorp")

if [ ! -f "$SQL_FILE" ]; then
    echo "Aucun script sql n'a été fournit"
    exit 1
fi

mysql -h "$db_host" -u "$db_user" -p"$db_pass" "$db_name" < "$SQL_FILE"
if [ $? -ne 0 ]; then
    echo "L'imprtation a échouée"
    exit 1
fi

cat > "$DEPLOY_CONFIG_PATH" <<EOL
<?php
define('ROOT', dirname(__DIR__));
define('APP_DIR', ROOT . '/app');
define('UTILS_DIR', APP_DIR . '/Utils');
define('CONTROLLERS_DIR', APP_DIR . '/Controllers');
define('MODELS_DIR', APP_DIR . '/Models');
define('VIEWS_DIR', APP_DIR . '/Views');
define('PUBLIC_DIR', APP_DIR . '/public');
define('UPLOADS_DIR', PUBLIC_DIR . '/uploads');
define('WEBSITE', '/');
define('DB_HOST', '$db_host');
define('DB_NAME', '$db_name');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
EOL

chown -R www-data:www-data "$DEPLOY_PATH"
chmod -R u+rwx,go+rw,go-w "$DEPLOY_PATH"
chmod a+rwx "$UPLOAD_PATH"

systemctl restart apache2

echo "La configuration du projet est terminée"