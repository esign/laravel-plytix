# A Laravel SDK for the Plytix API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-plytix.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-plytix)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-plytix.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-plytix)
![GitHub Actions](https://github.com/esign/laravel-plytix/actions/workflows/main.yml/badge.svg)

A short intro about the package.

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-plytix
```

The package will automatically register a service provider.

Next up, you can publish the configuration file:
```bash
php artisan vendor:publish --provider="Esign\Plytix\PlytixServiceProvider" --tag="config"
```

## Usage

### Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
