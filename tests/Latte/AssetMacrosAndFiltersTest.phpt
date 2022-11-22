<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Latte;

use Latte\Engine;
use Tester\Assert;
use Tester\TestCase;
use Latte\Loaders\StringLoader;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use SixtyEightPublishers\Asset\Tests\DI\ContainerFactory;
use function assert;

require __DIR__ . '/../bootstrap.php';

final class AssetMacrosAndFiltersTest extends TestCase
{
	private Engine $engine;

	protected function setUp(): void
	{
		parent::setUp();

		$container = ContainerFactory::create(__DIR__ . '/config.neon');
		$latteFactory = $container->getByType(LatteFactory::class);
		assert($latteFactory instanceof LatteFactory);
		$this->engine = $latteFactory->create();

		$this->engine->setLoader(new StringLoader());
	}

	public function testAssetMacro(): void
	{
		$this->assertLatte([
			['{asset "my/file.json"}', 'https://cdn.example.com/my/file.json?version=2.1'],
			['{asset "my/nested/file.json", "json_manifest_strategy"}', '/my/nested/file.abc123.json'],
		]);
	}

	public function testAssetVersionMacro(): void
	{
		$this->assertLatte([
			['{asset_version "my/file.json"}', '2.1'],
			['{asset_version "my/nested/file.json", "json_manifest_strategy"}', '/my/nested/file.abc123.json'],
		]);
	}

	public function testAssetFilter(): void
	{
		$defaultPackage = <<< LATTE
{var \$file = "my/file.json"}
{\$file|asset}
LATTE;

		$nestedPackage = <<< LATTE
{var \$file = "my/nested/file.json"}
{\$file|asset: json_manifest_strategy}
LATTE;

		$this->assertLatte([
			[$defaultPackage, 'https://cdn.example.com/my/file.json?version=2.1'],
			[$nestedPackage, '/my/nested/file.abc123.json'],
		]);
	}

	public function testAssetVersionFilter(): void
	{
		$defaultPackage = <<< LATTE
{var \$file = "my/file.json"}
{\$file|asset_version}
LATTE;

		$nestedPackage = <<< LATTE
{var \$file = "my/nested/file.json"}
{\$file|asset_version: json_manifest_strategy}
LATTE;

		$this->assertLatte([
			[$defaultPackage, '2.1'],
			[$nestedPackage, '/my/nested/file.abc123.json'],
		]);
	}

	private function assertLatte(array $data): void
	{
		foreach ($data as [$latteCode, $expectedOutput]) {
			$output = $this->engine->renderToString($latteCode);

			Assert::same($expectedOutput, $output);
		}
	}
}

(new AssetMacrosAndFiltersTest())->run();
