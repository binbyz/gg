# GG

This library is a PHP library required to use the GG Client. Please visit the following site: https://phpgg.kr

## About

**GG Client** is a debug client for PHP developers. Install the library and check the variables you want to output in **GG Client** with `gg($foo);`. The data storage feature allows you to retrieve it later. If you're a developer using the Laravel framework, you can automatically detect exception objects and check them directly in GG Client.

## Installation and Requirements

**GG Client** can be used as a package installed by the composer dependency management tool. If you're a vanilla PHP developer, you can use the following command to install the library. Before installing, make sure that the PHP version of your project is higher than `^7.4`. Note that the Laravel framework supports versions from `^7.2` and above.

### Global Installation via composer

```bash
$ composer global require beaverlabs/gg
```

### Porject Installation via composer

For projects that manage dependencies using composer, please install the library for your project.

```bash
$ composer require --dev beaverlabs/gg
```

## Laravel Framework

Projects using Laravel can install a package with added Collection and exception object detection features.

```bash
$ composer require --dev beaverlabs/laravel-gg
```

## 버그 제보

This is very important. Please report bugs in **GG Client** through the issues in the repository.

For bugs related to the library, please report them using the repositories below:

- [beaverlabs/gg](https://github.com/binbyz/gg)
- [beaverlabs/laravel-gg](https://github.com/binbyz/laravel-gg)
