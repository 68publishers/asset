<h1 align="center">Symfony Asset Component in Nette</h1>

<p align="center">Integration of <a href="https://github.com/symfony/asset">symfony/asset</a> into Nette Framework.</p>

<p align="center">
<a href="https://github.com/68publishers/asset/actions"><img alt="Checks" src="https://badgen.net/github/checks/68publishers/asset/master"></a>
<a href="https://coveralls.io/github/68publishers/asset?branch=master"><img alt="Coverage Status" src="https://coveralls.io/repos/github/68publishers/asset/badge.svg?branch=master"></a>
<a href="https://packagist.org/packages/68publishers/asset"><img alt="Total Downloads" src="https://badgen.net/packagist/dt/68publishers/asset"></a>
<a href="https://packagist.org/packages/68publishers/asset"><img alt="Latest Version" src="https://badgen.net/packagist/v/68publishers/asset"></a>
<a href="https://packagist.org/packages/68publishers/asset"><img alt="PHP Version" src="https://badgen.net/packagist/php/68publishers/asset"></a>
</p>

## Installation

The best way to install 68publishers/asset is using Composer:

```sh
$ composer require 68publishers/asset
```

## Usage

Simply register a compiler extension into DIC:

```neon
extensions:
	asset: SixtyEightPublishers\Asset\Bridge\Nette\DI\AssetExtension

asset:
	# your configuration
```

Configuration options are described in official [Symfony documentation](https://symfony.com/doc/6.0/reference/configuration/framework.html#assets)

## Usage in Latte templates

```latte
{* Use default package *}
<img src="{asset 'my/awesome/image.png'}" alt="..."></a>
<p>Version: {asset_version 'my/awesome/image.png'}</p>


{* Use "foo" package *}
<img src="{asset 'my/awesome/image.png', 'foo'}" alt="..."></a>
<p>Version: {asset_version 'my/awesome/image.png', 'foo'}</p>
```

You can also use a function equivalent. For example if you want to store result in variable:

```latte
{var $asset = asset('my/awesome/image.png')}
{var $asset = asset('my/awesome/image.png', 'foo')}

{var $version = asset_version('my/awesome/image.png')}
{var $version = asset_version('my/awesome/image.png', 'foo')}
```

## Contributing

Before opening a pull request, please check your changes using the following commands

```bash
$ make init # to pull and start all docker images

$ make cs.check
$ make stan
$ make tests.all
```
