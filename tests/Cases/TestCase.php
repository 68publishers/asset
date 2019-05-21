<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Cases;

use Nette;
use Tester;
use SixtyEightPublishers;

abstract class TestCase extends Tester\TestCase
{
	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		if (!defined('TEMP_PATH')) {
			define('TEMP_PATH', __DIR__ . '/../temp');
		}
	}

	/**
	 * @param string       $name
	 * @param string|array $config
	 *
	 * @return \Nette\DI\Container
	 */
	protected function createContainer(string $name, $config): Nette\DI\Container
	{
		$loader = new Nette\DI\ContainerLoader(TEMP_PATH . '/cache/Nette.Configurator', TRUE);
		$class = $loader->load(function (Nette\DI\Compiler $compiler) use ($config): void {
			$compiler->addExtension('latte', new Nette\Bridges\ApplicationDI\LatteExtension(TEMP_PATH . '/cache/latte', TRUE));
			$compiler->addExtension('asset', new SixtyEightPublishers\Asset\DI\AssetExtension());
			$compiler->addConfig([
				'parameters' => [
					'filesDir' => realpath(__DIR__ . '/../files'),
				],
			]);

			if (is_array($config)) {
				$compiler->addConfig($config);
			} elseif (is_file($config)) {
				$compiler->loadConfig($config);
			} else {
				$compiler->loadConfig(Tester\FileMock::create((string) $config, 'neon'));
			}
		}, $name);

		return new $class();
	}
}
