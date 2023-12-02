# GG

This library is a PHP library required to use the GG Client. Please visit the following site: https://phpgg.kr

## About

**GG Client** is a debug client for PHP developers. Install the library and check the variables you want to output in **GG Client** with `gg($foo);`. The data storage feature allows you to retrieve it later. If you're a developer using the Laravel framework, you can automatically detect exception objects and check them directly in GG Client.

## Installation and Requirements

**GG Client** can be used as a package installed by the composer dependency management tool. If you're a vanilla PHP developer, you can use the following command to install the library. Before installing, make sure that the PHP version of your project is higher than `^7.4`. Note that the Laravel framework supports versions from `^7.2` and above.

### Porject Installation via composer

For projects that manage dependencies using composer, please install the library for your project.

```bash
composer require --dev beaverlabs/gg
```

### Publishing GG Client

If you are using the Laravel framework, you can publish the GG Client to the public directory using the following command.

```bash
php artisan vendor:publish --provider="Beaverlabs\Gg\Providers\GgServiceProvider"
```

or

`--force` option can be used to overwrite existing files.

```bash
php artisan vendor:publish --provider="Beaverlabs\Gg\Providers\GgServiceProvider" --force
```

## environment variables

The following environment variables are required to use the library.

```dotenv
GG_ENABLED=true
GG_HOST=host.docker.internal
GG_EXCEPTION_LISTENER=false
GG_MODEL_QUERY_LISTENER=true
GG_HTTP_RESPONSE_LISTENER=true
```

## Bug Reports

This is very important. Please report bugs in **GG Client** through the issues in the repository.

For bugs related to the library, please report them using the repositories below:

- [beaverlabs/gg](https://github.com/binbyz/gg)
