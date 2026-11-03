# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.0.0] - 2026-11-03

### Added
- Support for `symfony/asset` `^8.0`.

### Changed
- Minimal PHP version is 8.3.
- Minimal versions of `nette/di` (`^3.1.3`) and `nette/utils` (`^3.2.10 || ^4.0.1`), older releases cannot be installed on PHP 8.3.
- The option `version` (main and nested packages) is cast to a string only from an integer or a float, a value of another type (e.g. a boolean) is an invalid configuration.
- Dev tooling: PHPStan 2 (level max for `src` including the compiler extension), Nette Tester 2.6+, PHP-CS-Fixer 3.89+, CI matrix PHP 8.3, 8.4 and 8.5 with the highest dependencies and PHP 8.3 with the lowest dependencies, docker dev environment with PHP 8.3, 8.4 and 8.5.

### Removed
- ❗ Support for PHP < 8.3.
- ❗ Support for Latte 2 (`AssetLatte2Extension`, `AssetMacroSet`).

## [3.3.4] - 2024-08-06

### Added
- Support for `symfony/asset` `^7.0`.

## [3.3.3] - 2023-03-24

### Changed
- `Nette\DI\Definitions\Statement` is accepted for the options `base_urls` and `packages[].base_urls`.

## [3.3.2] - 2023-02-10

### Changed
- Required version of `nette/utils` is `^3.2.5 || ^4.0`.
- PHPStan level `max`.

## [3.3.1] - 2022-11-29

### Changed
- `Makefile` is excluded from an export.

## [3.3.0] - 2022-11-29

### Changed
- ❗ The namespace of the compiler extension is `SixtyEightPublishers\Asset\Bridge\Nette\DI\AssetExtension` (was `SixtyEightPublishers\Asset\DI\AssetExtension`).
- Latte macros, functions and providers are registered in the DI container.

## [3.2.1] - 2022-11-24

### Added
- Latte functions `asset` and `asset_version`.

### Removed
- Unusable Latte filters `|asset` and `|asset_version`.

## [3.2.0] - 2022-11-24

### Added
- The option `strict_mode` for the main and nested packages.

### Removed
- ❗ Support for `symfony/asset` `4.x`, the minimal version is `5.4`.

## [3.1.0] - 2022-11-22

### Added
- Support for Latte `^3.0` (in addition to `^2.5`).

## [3.0.0] - 2022-11-21

### Changed
- Supported PHP versions are `7.4` to `8.2`.
- Supported versions of `symfony/asset` are `^4.0 || ^5.0 || ^6.0`.
- The compiler extension has been completely refactored.
- Tests, GitHub actions and PHPStan.

## [2.1.0] - 2022-07-24

### Changed
- `Nette\DI\Statement` is accepted for the options `base_path` and `base_urls`.

## [2.0] - 2020-10-27

### Changed
- Upgrade to Nette `3.x`.

## [1.1] - 2020-02-02

### Changed
- Supported versions of `symfony/asset` are `^4.2 || ^5.0`.
- Improved coding style and CI configuration.

### Removed
- Support for PHP `7.1`.

## [1.0.1] - 2019-06-03

### Changed
- CI configuration.

## [1.0] - 2019-05-21

### Added
- Initial release (Nette `^2.4`, `symfony/asset` `^4.2`).

[Unreleased]: https://github.com/68publishers/asset/compare/v4.0.0...HEAD
[4.0.0]: https://github.com/68publishers/asset/compare/v3.3.4...v4.0.0
[3.3.4]: https://github.com/68publishers/asset/compare/v3.3.3...v3.3.4
[3.3.3]: https://github.com/68publishers/asset/compare/v3.3.2...v3.3.3
[3.3.2]: https://github.com/68publishers/asset/compare/v3.3.1...v3.3.2
[3.3.1]: https://github.com/68publishers/asset/compare/v3.3.0...v3.3.1
[3.3.0]: https://github.com/68publishers/asset/compare/v3.2.1...v3.3.0
[3.2.1]: https://github.com/68publishers/asset/compare/v3.2.0...v3.2.1
[3.2.0]: https://github.com/68publishers/asset/compare/v3.1.0...v3.2.0
[3.1.0]: https://github.com/68publishers/asset/compare/v3.0.0...v3.1.0
[3.0.0]: https://github.com/68publishers/asset/compare/v2.1.0...v3.0.0
[2.1.0]: https://github.com/68publishers/asset/compare/v2.0...v2.1.0
[2.0]: https://github.com/68publishers/asset/compare/v1.1...v2.0
[1.1]: https://github.com/68publishers/asset/compare/v1.0.1...v1.1
[1.0.1]: https://github.com/68publishers/asset/compare/v1.0...v1.0.1
[1.0]: https://github.com/68publishers/asset/releases/tag/v1.0
