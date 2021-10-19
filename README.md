# Symfony Asset Component in Nette

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![Latest Version on Packagist][ico-version]][link-packagist]

This package integrates [symfony/asset](https://github.com/symfony/asset) into Nette Framework.
Configuration is almost same as Symfony's [configuration](https://symfony.com/doc/4.2/reference/configuration/framework.html#assets).

## Installation

The best way to install 68publishers/asset is using Composer:

```bash
composer require 68publishers/asset
```

then you can register extension into DIC:

```yaml
extensions:
    asset: SixtyEightPublishers\Asset\DI\AssetExtension
```

## Confiugration

Configuration options are described in official [Symfony documentation](https://symfony.com/doc/4.2/reference/configuration/framework.html#assets)

## Usage in Latte templates

```latte
{* Use default package *}
<img src="{asset 'my/awesome/image.png'}" alt="..."></a>
<p>Version: {asset_version 'my/awesome/image.png'}</p>


{* Use "foo" package *}
<img src="{asset 'my/awesome/image.png', 'foo'}" alt="..."></a>
<p>Version: {asset_version 'my/awesome/image.png', 'foo'}</p>
```

You can also use Filter/Helper equivalent. For example if you want to store result in variable:

```latte
{var img = ('my/awesome/image.png')|asset}
{var imgFoo = ('my/awesome/image.png', 'foo')|asset}

{var version = ('my/awesome/image.png')|asset_version}
{var versionFoo = ('my/awesome/image.png', 'foo')|asset_version}
```

## Contributing

Before committing any changes, don't forget to run

```bash
vendor/bin/php-cs-fixer fix -v --dry-run
```

and

```bash
vendor/bin/tester ./tests
```

[ico-version]: https://img.shields.io/packagist/v/68publishers/asset.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/68publishers/asset/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/68publishers/asset.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/68publishers/asset.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/68publishers/asset.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/68publishers/asset
[link-travis]: https://travis-ci.org/68publishers/asset
[link-scrutinizer]: https://scrutinizer-ci.com/g/68publishers/asset/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/68publishers/asset
[link-downloads]: https://packagist.org/packages/68publishers/asset
