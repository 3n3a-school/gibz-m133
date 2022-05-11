# Ranglisten - M133

_Webapplikation mit Session Handling realisieren_

## Usage

### Docker

Start the application with `Docker-Compose`:
 
```bash
sudo docker-compose up --build -d
```

1. Once the application is up and running open your browser
2. Go to the URL: `host:port/`
3. Now the application should automatically go to _install.php_ \
and begin installing itself.
4. Once done you should be up and running with the following admin creds: `admin:admin`

### XAMPP

1. In `config.php` comment the lines after _For Docker_ and uncomment _For XAMPP_
2. Create a User in _PHPMyAdmin_ with the name **m133** and the password _1234_.
3. Create a Database with the name **m133**
4. Add these files in this directory to **htdocs** and head on over to _localhost:80_
5. Now the database structure should be automatically created
6. Once done you should be up and running with the following admin creds: `admin:admin`

## Import of Ranking Lists

* Can import csv's from `o-l.ch`, where is _kind=all&csv=1_

1. Download from _o-l.ch_
2. Change file extension to `.csv`
3. Open in Sublime Text and _Save with Encoding > UTF-8_
4. Import in Webapp


## Developer Info

### Known Bugs

* Templating engine:
    * Cannot have two template tags on one line
