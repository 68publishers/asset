<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Bridge\Nette\DI;

use Tester\Assert;
use Tester\TestCase;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\PathPackage;
use Nette\DI\InvalidConfigurationException;
use Symfony\Component\Asset\Exception\AssetNotFoundException;
use function assert;
use function strpos;

require __DIR__ . '/../../../bootstrap.php';

final class AssertExtensionTest extends TestCase
{
	public function testExceptionShouldBeThrownWhenBasePathAndBaseUrlsCombinedInDefaultPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.defaultPackage.basePathAndBaseUrlsCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'base_path' and 'base_urls' at the same time.' for item 'asset' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\AssetConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenBasePathAndBaseUrlsCombinedInNestedPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.nestedPackage.basePathAndBaseUrlsCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'base_path' and 'base_urls' at the same time.' for item 'asset\u{a0}›\u{a0}packages\u{a0}›\u{a0}test_package' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\PackageConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionAndJsonManifestPathCombinedInDefaultPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.defaultPackage.versionAndJsonManifestPathCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version' and 'json_manifest_path' at the same time.' for item 'asset' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\AssetConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionAndJsonManifestPathCombinedInNestedPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.nestedPackage.versionAndJsonManifestPathCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version' and 'json_manifest_path' at the same time.' for item 'asset\u{a0}›\u{a0}packages\u{a0}›\u{a0}test_package' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\PackageConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionStrategyAndJsonManifestPathCombinedInDefaultPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.defaultPackage.versionStrategyAndJsonManifestPathCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version_strategy' and 'json_manifest_path' at the same time.' for item 'asset' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\AssetConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionStrategyAndJsonManifestPathCombinedInNestedPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.nestedPackage.versionStrategyAndJsonManifestPathCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version_strategy' and 'json_manifest_path' at the same time.' for item 'asset\u{a0}›\u{a0}packages\u{a0}›\u{a0}test_package' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\PackageConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionStrategyAndVersionCombinedInDefaultPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.defaultPackage.versionStrategyAndVersionCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version_strategy' and 'version' at the same time.' for item 'asset' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\AssetConfig."
		);
	}

	public function testExceptionShouldBeThrownWhenVersionStrategyAndVersionCombinedInNestedPackage(): void
	{
		Assert::exception(
			static fn () => ContainerFactory::create(__DIR__ . '/error.nestedPackage.versionStrategyAndVersionCombination.neon'),
			InvalidConfigurationException::class,
			"Failed assertion 'You cannot use both 'version_strategy' and 'version' at the same time.' for item 'asset\u{a0}›\u{a0}packages\u{a0}›\u{a0}test_package' with value object SixtyEightPublishers\\Asset\\Bridge\\Nette\\DI\\PackageConfig."
		);
	}

	public function testMinimalConfiguration(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/minimal.neon');
		$packages = $container->getService('asset.packages');
		assert($packages instanceof Packages);

		Assert::type(Packages::class, $packages);

		$this->assertPackage(
			$packages,
			NULL,
			PathPackage::class,
			'/my/image.png',
			''
		);
	}

	public function testFullFeaturedConfiguration(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/fullFeatured.neon');
		$packages = $container->getService('asset.packages');
		assert($packages instanceof Packages);

		Assert::type(Packages::class, $packages);

		$this->assertPackage(
			$packages,
			NULL,
			UrlPackage::class,
			'https://cdn.example.com/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'images_path',
			PathPackage::class,
			'/foo/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'images',
			UrlPackage::class,
			'https://images1.example.com/my/image.png?version=1.0.0|https://images2.example.com/my/image.png?version=1.0.0',
			'1.0.0'
		);

		$this->assertPackage(
			$packages,
			'foo',
			PathPackage::class,
			'/my/image.png-1.0.0',
			'1.0.0'
		);

		$this->assertPackage(
			$packages,
			'bar',
			UrlPackage::class,
			'https://bar2.example.com/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'bar_version_strategy',
			UrlPackage::class,
			'https://bar_version_strategy.example.com/my/image.png-FOO',
			'-FOO'
		);

		$this->assertPackage(
			$packages,
			'json_manifest_strategy',
			PathPackage::class,
			'/my/image.abc123.png',
			'/my/image.abc123.png'
		);

		# strict mode disabled (by default)
		$this->assertPackage(
			$packages,
			'json_manifest_strategy',
			PathPackage::class,
			'/missing-image.png',
			'/missing-image.png',
			'/missing-image.png'
		);

		$this->assertPackage(
			$packages,
			'json_manifest_strategy_strict',
			PathPackage::class,
			'/my/image.abc123.png',
			'/my/image.abc123.png'
		);

		# strict mode enabled
		Assert::exception(
			fn () => $this->assertPackage(
				$packages,
				'json_manifest_strategy_strict',
				PathPackage::class,
				'',
				'',
				'/missing-image.png'
			),
			AssetNotFoundException::class,
			'Asset "/missing-image.png" not found in manifest "%a%/DI/manifest.json".%A?%'
		);
	}

	public function testAssetsDefaultVersionStrategyAsService(): void
	{
		$container = ContainerFactory::create(__DIR__ . '/defaultVersionStrategyAsService.neon');

		/** @var \Symfony\Component\Asset\Packages $packages */
		$packages = $container->getService('asset.packages');

		$this->assertPackage(
			$packages,
			NULL,
			UrlPackage::class,
			'https://cdn.example.com/my/image.png-FOO',
			'-FOO'
		);
	}

	private function assertPackage(Packages $packages, ?string $name, string $type, string $url, string $version, string $path = 'my/image.png'): void
	{
		Assert::noError(static function () use ($packages, $name) {
			$packages->getPackage($name);
		});

		$package = $packages->getPackage($name);

		Assert::type($type, $package);
		Assert::same($version, $package->getVersion($path));

		if (strpos($url, '|') !== FALSE) {
			Assert::contains($package->getUrl($path), explode('|', $url));
		} else {
			Assert::same($url, $package->getUrl($path));
		}
	}
}

(new AssertExtensionTest())->run();
