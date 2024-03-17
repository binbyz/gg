# GG

This library is a PHP library required to use the GG Client. Please visit the following site: http://phpgg.kr

## Important Notice

This library moved to new vendor(~~beaverlabs/gg~~) name. Please use the following command to install the library.

```shell
composer require --dev eyedroot/gg
```

## About

**GG Client** is a debug client for PHP developers. Install the library and check the variables you want to output in **GG Client** with `gg($foo);`. The data storage feature allows you to retrieve it later. If you're a developer using the Laravel framework, you can automatically detect exception objects and check them directly in GG Client.

## Installation and Requirements

**GG Client** can be used as a package installed by the composer dependency management tool. If you're a vanilla PHP developer, you can use the following command to install the library. Before installing, make sure that the PHP version of your project is higher than `^8.3`. Note that the Laravel framework supports versions from `^9.0` and above.

## Support PHP Version

- `beaverlabs/gg:v2.0.0` requires PHP version `^8.3`
- `beaverlabs/gg:v1.5.3` requires PHP version `^7.4`

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
