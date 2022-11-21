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

final class AssetMacroSetTest extends TestCase
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
		$this->assertMacros([
			['{asset "my/file.json"}', 'https://cdn.example.com/my/file.json?version=2.1'],
			['{asset "my/nested/file.json", "json_manifest_strategy"}', '/my/nested/file.abc123.json'],
		]);
	}

	public function testAssetVersionMacro(): void
	{
		$this->assertMacros([
			['{asset_version "my/file.json"}', '2.1'],
			['{asset_version "my/nested/file.json", "json_manifest_strategy"}', '/my/nested/file.abc123.json'],
		]);
	}

	private function assertMacros(array $data): void
	{
		foreach ($data as [$latteCode, $expectedOutput]) {
			$output = $this->engine->renderToString($latteCode);

			Assert::same($expectedOutput, $output);
		}
	}
}

(new AssetMacroSetTest())->run();
