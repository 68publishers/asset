<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\VersionStrategy;

use SixtyEightPublishers\Asset\VersionStrategy\CacheAssets;
use SixtyEightPublishers\Asset\VersionStrategy\FileModifyTimeStrategy;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class FileModifyTimeStrategyTest extends TestCase
{

	public function testVersionLikeMTime(): void
	{
		$tempDir = sys_get_temp_dir() . '/' . uniqid('68publishers:AssetExtensionTest', true);
		$wwwDir = "$tempDir/www";
		@mkdir("$wwwDir/js", 0755, true);

		$fileA = 'js/a.js';
		$pathA = "$wwwDir/$fileA";
		$unixtimeA = 1678000000;
		touch($pathA, $unixtimeA);

		$cacheAssets = new CacheAssets(true, $tempDir);
		$fileModifyStrategy = new FileModifyTimeStrategy($wwwDir, $cacheAssets);

		Assert::same((string) $unixtimeA, $fileModifyStrategy->getVersion($fileA));
		Assert::same("$fileA?$unixtimeA", $fileModifyStrategy->applyVersion($fileA));
		Assert::same("/$fileA?$unixtimeA", $fileModifyStrategy->applyVersion("/$fileA"));

		$unixtimeB = 1679000000;
		touch($pathA, $unixtimeB);
		Assert::same((string) $unixtimeA, $fileModifyStrategy->getVersion($fileA));
		$cacheAssets->clear();
		clearstatcache();
		Assert::same((string) $unixtimeB, $fileModifyStrategy->getVersion($fileA));
	}

}

(new FileModifyTimeStrategyTest())->run();
