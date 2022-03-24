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

## Import of Ranking Lists

* Can import csv's from `o-l.ch`, where is _kind=all&csv=1_

1. Download from _o-l.ch_
2. Change file extension to `.csv`
3. Open in Sublime Text and _Save with Encoding > UTF-8_
4. Import in Webapp

### Installation

1. Once the application is up and running open your browser
2. Go to the URL: `host:port/`
3. Now the application should automatically go to _install.php_ \
and begin installing itself.
4. Once done you should be up and running with the following admin creds: `admin:admin`

## Developer Info

### Known Bugs

* Templating engine:
    * Cannot have two template tags on one line