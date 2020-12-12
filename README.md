# Marvel API

Symfony 5.2

## Getting started

### Environment

* PHP 7.2.5 or higher
* Composer 1.8 or higher

### Installation

To use this API, you must clone this repository

```shell
git clone git@github.com:Garinlu/peaks_marvel_api.git
```

Then, install all bundles :

```shell
composer install
```

To finish, you must serve your application. There is an example for Nginx :

```
server {
	listen 8005;
    root /PATH/TO/FILES/public;
	client_max_body_size 50M;

    location / {
		try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
		fastcgi_buffers 16 16k;
		fastcgi_buffer_size 32k;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        include fastcgi_params;
	}
}
```

## Test

To run Test, run :
```shell
php bin/phpunit
```
