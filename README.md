# Symfony Asset Component in Nette

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
{var img = ('my/awesome/image.png')|getUrl}
{var imgFoo = ('my/awesome/image.png', 'foo')|getUrl}

{var version = ('my/awesome/image.png')|getVersion}
{var versionFoo = ('my/awesome/image.png', 'foo')|getVersion}
```

## Contributing

Before committing any changes, don't forget to run

```bash
vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
vendor/bin/tester ./tests
```
