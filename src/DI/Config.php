<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette;

final class Config
{
	use Nette\SmartObject;

	const 	MAIN_PACKAGE_DEFAULT = [
		'base_path' => '',
		'base_urls' => [],
		'version' => NULL,
		'version_format' => '%s?%s',
		'version_strategy' => NULL,
		'json_manifest_path' => NULL,
		'packages' => [],
	];

	const 	PACKAGE_DEFAULTS = [
		'base_path' => NULL,
		'base_urls' => [],
		'version' => NULL,
		'version_format' => NULL,
		'version_strategy' => NULL,
		'json_manifest_path' => NULL,
	];

	/**
	 * @return array
	 */
	public function getDefaults(): array
	{
		return self::MAIN_PACKAGE_DEFAULT;
	}

	/**
	 * @param \Nette\DI\CompilerExtension $extension
	 *
	 * @return array
	 * @throws \Nette\Utils\AssertionException
	 */
	public function getConfig(Nette\DI\CompilerExtension $extension): array
	{
		$config = $this->mergeConfig($extension, $this->getDefaults(), $extension->getConfig());

		$config = $this->validatePackage($config, TRUE);

		foreach ($config['packages'] as $name => $package) {
			Nette\Utils\Validators::assert($package, 'array');

			$config['packages'][(string) $name] = $this->validatePackage(
				$this->mergeConfig($extension, self::PACKAGE_DEFAULTS, $package),
				FALSE
			);
		}

		return $config;
	}

	/**
	 * @param \Nette\DI\CompilerExtension $extension
	 * @param array                       $defaults
	 * @param array                       $config
	 *
	 * @return array
	 */
	private function mergeConfig(Nette\DI\CompilerExtension $extension, array $defaults, array $config): array
	{
		/** @noinspection PhpInternalEntityUsedInspection */
		return $extension->validateConfig(
			Nette\DI\Helpers::expand($defaults, $extension->getContainerBuilder()->parameters),
			$config
		);
	}

	/**
	 * @param array $package
	 * @param bool  $isDefault
	 *
	 * @return array
	 * @throws \Nette\Utils\AssertionException
	 */
	private function validatePackage(array $package, bool $isDefault): array
	{
		Nette\Utils\Validators::assertField($package, 'version_strategy', 'string|null|' . Nette\DI\Statement::class);
		Nette\Utils\Validators::assertField($package, 'version', 'string|null');
		Nette\Utils\Validators::assertField($package, 'version_format', TRUE === $isDefault ? 'string' : 'string|null');
		Nette\Utils\Validators::assertField($package, 'json_manifest_path', 'string|null');
		Nette\Utils\Validators::assertField($package, 'base_path', 'string|null');
		Nette\Utils\Validators::assertField($package, 'base_urls', 'string|string[]');

		if (is_string($package['base_urls'])) {
			$package['base_urls'] = [ $package['base_urls'] ];
		}

		if (isset($package['version_strategy']) && isset($package['version'])) {
			throw new \LogicException('You cannot use both "version_strategy" and "version" at the same time under "assets" packages.');
		}

		if (isset($package['version_strategy']) && isset($package['json_manifest_path'])) {
			throw new \LogicException('You cannot use both "version_strategy" and "json_manifest_path" at the same time under "assets" packages.');
		}

		if (isset($package['version']) && isset($package['json_manifest_path'])) {
			throw new \LogicException('You cannot use both "version" and "json_manifest_path" at the same time under "assets" packages.');
		}

		return $package;
	}
}
