<VirtualHost *:80>
	ServerName {servername}

	DocumentRoot {document_root}
	<Directory />
		Options FollowSymLinks
		AllowOverride all
	</Directory>
	<Directory {document_root}>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride all
		Order allow,deny
		allow from all
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
