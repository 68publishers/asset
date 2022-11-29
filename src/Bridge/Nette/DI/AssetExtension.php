<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Nette\DI;

use Latte\Engine;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\ContainerBuilder;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\Statement;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\PathPackage;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Symfony\Component\Asset\PackageInterface;
use SixtyEightPublishers\Asset\Bridge\Latte\AssetLatte2Extension;
use SixtyEightPublishers\Asset\Bridge\Latte\AssetLatte3Extension;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use function assert;
use function is_array;
use function is_string;

final class AssetExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		$assertVersionStrategyAndVersionCombination = static fn (object $package): bool => !isset($package->version_strategy, $package->version);
		$assertVersionStrategyAndJsonManifestPathCombination = static fn (object $package): bool => !isset($package->version_strategy, $package->json_manifest_path);
		$assertVersionAndJsonManifestPathCombination = static fn (object $package): bool => !isset($package->version, $package->json_manifest_path);
		$assertBasePathAndBaseUrlsCombination = static fn (object $package): bool => !(!empty($package->base_path) && !empty($package->base_urls));

		$packageStructure = Expect::structure([
			'base_path' => Expect::string()
				->nullable(),
			'base_urls' => Expect::anyOf(Expect::string(), Expect::listOf('string'))
				->default([])
				->before(static fn ($val): array => !is_array($val) ? [$val] : $val),
			'version' => Expect::anyOf(Expect::string(), Expect::int(), Expect::float())
				->nullable()
				->before(static fn ($val) => NULL !== $val ? (string) $val : NULL),
			'version_format' => Expect::string()
				->nullable(),
			'version_strategy' => Expect::anyOf(Expect::type(Statement::class), Expect::string())
				->nullable()
				->before(static fn ($strategy): ?Statement => is_string($strategy) ? new Statement($strategy) : $strategy),
			'json_manifest_path' => Expect::string()
				->nullable(),
			'strict_mode' => Expect::bool(FALSE),
		])->assert($assertBasePathAndBaseUrlsCombination, 'You cannot use both \'base_path\' and \'base_urls\' at the same time.')
			->assert($assertVersionStrategyAndVersionCombination, 'You cannot use both \'version_strategy\' and \'version\' at the same time.')
			->assert($assertVersionStrategyAndJsonManifestPathCombination, 'You cannot use both \'version_strategy\' and \'json_manifest_path\' at the same time.')
			->assert($assertVersionAndJsonManifestPathCombination, 'You cannot use both \'version\' and \'json_manifest_path\' at the same time.')
			->castTo(PackageConfig::class);

		return Expect::structure([
			'base_path' => Expect::string(''),
			'base_urls' => Expect::anyOf(Expect::string(), Expect::listOf('string'))
				->default([])
				->before(static fn ($val): array => !is_array($val) ? [$val] : $val),
			'version' => Expect::anyOf(Expect::string(), Expect::int(), Expect::float())
				->nullable()
				->before(static fn ($val) => NULL !== $val ? (string) $val : NULL),
			'version_format' => Expect::string('%s?%s'),
			'version_strategy' => Expect::anyOf(Expect::type(Statement::class), Expect::string())
				->nullable()
				->before(static fn ($strategy): ?Statement => is_string($strategy) ? new Statement($strategy) : $strategy),
			'json_manifest_path' => Expect::string()
				->nullable(),
			'strict_mode' => Expect::bool(FALSE),
			'packages' => Expect::arrayOf($packageStructure, 'string'),
		])->assert($assertBasePathAndBaseUrlsCombination, 'You cannot use both \'base_path\' and \'base_urls\' at the same time.')
			->assert($assertVersionStrategyAndVersionCombination, 'You cannot use both \'version_strategy\' and \'version\' at the same time.')
			->assert($assertVersionStrategyAndJsonManifestPathCombination, 'You cannot use both \'version_strategy\' and \'json_manifest_path\' at the same time.')
			->assert($assertVersionAndJsonManifestPathCombination, 'You cannot use both \'version\' and \'json_manifest_path\' at the same time.')
			->castTo(AssetConfig::class);
	}

	public function loadConfiguration(): void
	{
		$packages = [];
		$config = $this->config;
		assert($config instanceof AssetConfig);

		$defaultVersionStrategy = $this->createVersionStrategy('_default', $config);
		$defaultPackage = $this->createPackage('_default', $config, $defaultVersionStrategy);

		foreach ($config->packages as $name => $package) {
			$package->version_format = $package->version_format ?? $config->version_format;
			$versionStrategy = $this->createVersionStrategy($name, $package, $defaultVersionStrategy);
			$packages[$name] = $this->createPackage($name, $package, $versionStrategy);
		}

		$this->getContainerBuilder()
			->addDefinition($this->prefix('packages'))
			->setType(Packages::class)
			->setArguments([
				'defaultPackage' => $defaultPackage,
				'packages' => $packages,
			]);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$latteFactory = $builder->getDefinition($builder->getByType(Engine::class) ?? 'nette.latteFactory');
		assert($latteFactory instanceof FactoryDefinition);
		$resultDefinition = $latteFactory->getResultDefinition();

		if (version_compare(Engine::VERSION, '3', '<')) {
			$resultDefinition->addSetup('?::extend(?, ?)', [
				ContainerBuilder::literal(AssetLatte2Extension::class),
				new Reference('self'),
				new Reference($this->prefix('packages')),
			]);

			return;
		}

		$resultDefinition->addSetup('addExtension', [
			new Statement(AssetLatte3Extension::class, [
				new Reference($this->prefix('packages')),
			]),
		]);
	}

	private function createVersionStrategy(string $packageName, PackageConfig $config, ?ServiceDefinition $default = NULL): ServiceDefinition
	{
		$statement = (static function (PackageConfig $config): ?Statement {
			if ($config->version_strategy instanceof Statement) {
				return $config->version_strategy;
			}

			if (NULL !== $config->version) {
				return new Statement(StaticVersionStrategy::class, [
					'version' => (string) $config->version,
					'format' => (string) $config->version_format,
				]);
			}

			if (NULL !== $config->json_manifest_path) {
				return new Statement(JsonManifestVersionStrategy::class, [
					'manifestPath' => $config->json_manifest_path,
					'strictMode' => $config->strict_mode,
				]);
			}

			return NULL;
		})($config);

		if (NULL === $statement && NULL !== $default) {
			return $default;
		}

		return $this->getContainerBuilder()
			->addDefinition($this->prefix('version_strategy.' . $packageName))
			->setType(VersionStrategyInterface::class)
			->setFactory($statement ?? new Statement(EmptyVersionStrategy::class))
			->setAutowired(FALSE);
	}

	private function createPackage(string $packageName, PackageConfig $config, ServiceDefinition $versionStrategy): ServiceDefinition
	{
		$statement = (static function (PackageConfig $config, ServiceDefinition $versionStrategy): Statement {
			if (empty($config->base_urls)) {
				return new Statement(PathPackage::class, [
					'basePath' => $config->base_path ?? '',
					'versionStrategy' => $versionStrategy,
				]);
			}

			return new Statement(UrlPackage::class, [
				'baseUrls' => $config->base_urls,
				'versionStrategy' => $versionStrategy,
			]);
		})($config, $versionStrategy);

		return $this->getContainerBuilder()
			->addDefinition($this->prefix('package.' . $packageName))
			->setType(PackageInterface::class)
			->setFactory($statement)
			->setAutowired(FALSE);
	}
}
