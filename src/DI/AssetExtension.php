<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Latte;
use Nette;
use Symfony;
use SixtyEightPublishers;

final class AssetExtension extends Nette\DI\CompilerExtension
{
	/** @var \SixtyEightPublishers\Asset\DI\PackageDefinitionFacade  */
	private $packages;

	/** @var \SixtyEightPublishers\Asset\DI\VersionDefinitionFacade  */
	private $versions;

	public function __construct()
	{
		$reference = new ReferenceFacade($this);
		$this->packages = new PackageDefinitionFacade($reference);
		$this->versions = new VersionDefinitionFacade($reference);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Nette\Utils\AssertionException
	 */
	public function loadConfiguration(): void
	{
		$namedPackages = [];
		$config = (new Config())->getConfig($this);

		$defaultVersion = NULL !== $config['version_strategy']
			? new Nette\DI\Statement($this->versions->getVersionDependencyReference($config['version_strategy'], '_default'))
			: $this->versions->createVersionStatement('_default', $config['version'], $config['version_format'], $config['json_manifest_path']);

		$defaultPackage = $this->packages->createPackageStatement('_default', $config['base_path'], $config['base_urls'], $defaultVersion);

		foreach ($config['packages'] as $name => $package) {
			$namedPackages[$name] = $this->createPackage((string) $name, $package, $config, $defaultVersion);
		}

		$this->getContainerBuilder()
			->addDefinition($this->prefix('packages'))
			->setType(Symfony\Component\Asset\Packages::class)
			->setArguments([
				'defaultPackage' => $defaultPackage,
				'packages' => $namedPackages,
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$latteFactory = $builder->getDefinition($builder->getByType(Latte\Engine::class) ?? 'nette.latteFactory');

		# asset filters
		$latteFactory->getResultDefinition()->addSetup('addFilter', [
			'name' => 'asset',
			'callback' => [ $this->prefix('@packages'), 'getUrl' ],
		]);

		$latteFactory->getResultDefinition()->addSetup('addFilter', [
			'name' => 'asset_version',
			'callback' => [ $this->prefix('@packages'), 'getVersion' ],
		]);

		# asset macros
		$latteFactory->getResultDefinition()->addSetup('addProvider', [
			'name' => 'symfonyPackages',
			'value' => $this->prefix('@packages'),
		]);

		$latteFactory->getResultDefinition()->addSetup('?->onCompile[] = function ($engine) { ?::install($engine->getCompiler()); }', [
			'@self',
			new Nette\PhpGenerator\PhpLiteral(SixtyEightPublishers\Asset\Latte\AssetMacroSet::class),
		]);
	}

	/**
	 * @param string              $name
	 * @param array               $package
	 * @param array               $config
	 * @param \Nette\DI\Statement $defaultVersion
	 *
	 * @return \Nette\DI\Statement
	 */
	private function createPackage(string $name, array $package, array $config, Nette\DI\Statement $defaultVersion): Nette\DI\Statement
	{
		if (NULL !== $package['version_strategy']) {
			$version = new Nette\DI\Statement($this->versions->getVersionDependencyReference($package['version_strategy'], $name));
		} elseif (NULL === $package['version'] && NULL === $package['json_manifest_path']) {
			// if neither version nor json_manifest_path are specified, use the default
			$version = $defaultVersion;
		} else {
			// let format fallback to main version_format
			$version = $this->versions->createVersionStatement($name, !empty($package['version']) ? $package['version'] : NULL, $package['version_format'] ?: $config['version_format'], $package['json_manifest_path']);
		}

		return $this->packages->createPackageStatement($name, $package['base_path'], $package['base_urls'], $version);
	}
}
