<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Cases\Latte;

use Latte;
use Tester;
use SixtyEightPublishers;

require __DIR__ . '/../../bootstrap.php';

final class AssetMacroSetTest extends Tester\TestCase
{
	/** @var NULL|\Nette\DI\Container */
	private $container;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->container = SixtyEightPublishers\Asset\Tests\Helper\ContainerFactory::createContainer(__METHOD__, __DIR__ . '/../../files/assets.neon');
	}

	/**
	 * @return void
	 */
	public function testAssetMacro(): void
	{
		$latte = $this->createLatte();

		Tester\Assert::same('http://cdn.example.com/my/first/file.png?version=SomeVersionScheme', $latte->renderToString('{asset "my/first/file.png"}'));
		Tester\Assert::same('/my/second/file.abc123.png', $latte->renderToString('{asset "my/second/file.png", "json_manifest_strategy"}'));
	}

	/**
	 * @return void
	 */
	public function testAssetVersionMacro(): void
	{
		$latte = $this->createLatte();

		Tester\Assert::same('SomeVersionScheme', $latte->renderToString('{asset_version "my/first/file.png"}'));
		Tester\Assert::same('1.0.0', $latte->renderToString('{asset_version "my/second/file.png", "images"}'));
	}

	/**
	 * @return \Latte\Engine
	 */
	private function createLatte(): Latte\Engine
	{
		/** @var \Nette\Bridges\ApplicationLatte\ILatteFactory $latteFactory */
		$latteFactory = $this->container->getService('latte.latteFactory');
		$latte = $latteFactory->create();
		$latte->setLoader(new Latte\Loaders\StringLoader());

		return $latte;
	}
}

(new AssetMacroSetTest())->run();
