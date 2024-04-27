![drSoft.fr](logo.png)

# drSoft.fr ReCAPTCHA

## Table of contents

- [Presentation](#Presentation)
- [Requirements](#Requirements)
- [Install](#Install)
- [Links](#Links)
- [Authors](#Authors)
- [Licenses](#Licenses)

## Presentation

PrestaShop module to use Google reCAPTCHA

## Requirements

This module requires PrestaShop 1.7.8 to work correctly.

This library also requires :

for production :

- [composer](https://getcomposer.org/)

for development :

- [composer](https://getcomposer.org/)

## Install

```bash
$ cd {PRESTASHOP_FOLDER}/modules
$ git clone git@github.com:drsoft-fr/prestashop-module-drsoftfrrecaptcha.git
$ mv prestashop-module-drsoftfrrecaptcha drsoftfrrecaptcha
$ cd drsoftfrrecaptcha
$ composer install -o --no-dev
$ cd {PRESTASHOP_FOLDER}
$ php ./bin/console prestashop:module install drsoftfrrecaptcha
```

## Links

- [drSoft.fr on GitHub](https://github.com/drsoft-fr)
- [GitHub](https://github.com/drsoft-fr/prestashop-module-drsoftfrrecaptcha)
- [Issues](https://github.com/drsoft-fr/prestashop-module-drsoftfrrecaptcha/issues)

## Authors

**Dylan Ramos** - [on GitHub](https://github.com/dylan-ramos)

## Licenses

MIT
