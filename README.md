# Ranglisten - M133

_Webapplikation mit Session Handling realisieren_

## Usage

Start the application with `Docker-Compose`:

```bash
sudo docker-compose up --build -d
```

To start the application on different infrastructure just make sure that
the following PHP-Extensions are present on your installation.

* intl
* pdo
* pdo_mysql

Afterwards you should make sure, that all requests except for files
are redirected to the `index.php` file in the _backend_ folder. For Apache
Servers this should already be present in the `.htaccess` file.
