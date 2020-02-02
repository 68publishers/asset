<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Cases\DI;

use Tester;
use Symfony;
use SixtyEightPublishers;

require __DIR__ . '/../../bootstrap.php';

final class AssertExtensionTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testAssets(): void
	{
		$container = SixtyEightPublishers\Asset\Tests\Helper\ContainerFactory::createContainer(__METHOD__, __DIR__ . '/../../files/assets.neon');

		/** @var \Symfony\Component\Asset\Packages $packages */
		$packages = $container->getService('asset.packages');

		Tester\Assert::type(Symfony\Component\Asset\Packages::class, $packages);

		$this->assertPackage(
			$packages,
			NULL,
			Symfony\Component\Asset\UrlPackage::class,
			'http://cdn.example.com/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'images_path',
			Symfony\Component\Asset\PathPackage::class,
			'/foo/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'images',
			Symfony\Component\Asset\UrlPackage::class,
			'http://images1.example.com/my/image.png?version=1.0.0|http://images2.example.com/my/image.png?version=1.0.0',
			'1.0.0'
		);

		$this->assertPackage(
			$packages,
			'foo',
			Symfony\Component\Asset\PathPackage::class,
			'/my/image.png-1.0.0',
			'1.0.0'
		);

		$this->assertPackage(
			$packages,
			'bar',
			Symfony\Component\Asset\UrlPackage::class,
			'https://bar2.example.com/my/image.png?version=SomeVersionScheme',
			'SomeVersionScheme'
		);

		$this->assertPackage(
			$packages,
			'bar_version_strategy',
			Symfony\Component\Asset\UrlPackage::class,
			'https://bar_version_strategy.example.com/my/image.png-FOO',
			'-FOO'
		);

		$this->assertPackage(
			$packages,
			'json_manifest_strategy',
			Symfony\Component\Asset\PathPackage::class,
			'/my/image.abc123.png',
			'/my/image.abc123.png'
		);
	}

	/**
	 * @return void
	 */
	public function testAssetsDefaultVersionStrategyAsService(): void
	{
		$container = SixtyEightPublishers\Asset\Tests\Helper\ContainerFactory::createContainer(__METHOD__, __DIR__ . '/../../files/assets_version_strategy_as_service.neon');

		/** @var \Symfony\Component\Asset\Packages $packages */
		$packages = $container->getService('asset.packages');

		$this->assertPackage(
			$packages,
			NULL,
			Symfony\Component\Asset\UrlPackage::class,
			'http://cdn.example.com/my/image.png-FOO',
			'-FOO'
		);
	}

	/**
	 * @param \Symfony\Component\Asset\Packages $packages
	 * @param string|NULL                       $name
	 * @param string                            $type
	 * @param string                            $url
	 * @param string                            $version
	 * @param string                            $path
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function assertPackage(Symfony\Component\Asset\Packages $packages, ?string $name, string $type, string $url, string $version, string $path = 'my/image.png'): void
	{
		# package must be defined
		Tester\Assert::noError(static function () use ($packages, $name) {
			$packages->getPackage($name);
		});

		$package = $packages->getPackage($name);

		Tester\Assert::type($type, $package);
		Tester\Assert::same($version, $package->getVersion($path));

		if (strpos($url, '|') !== FALSE) {
			Tester\Assert::contains($package->getUrl($path), explode('|', $url));
		} else {
			Tester\Assert::same($url, $package->getUrl($path));
		}
	}
}

(new AssertExtensionTest())->run();
