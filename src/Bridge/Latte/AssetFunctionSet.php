<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Latte;

use Symfony\Component\Asset\Packages;

/**
 * @internal
 */
final class AssetFunctionSet
{
	private function __construct()
	{
	}

	/**
	 * @return array<string, callable>
	 */
	public static function functions(Packages $packages): array
	{
		return [
			'asset' => static fn (string $path, ?string $packageName = NULL): string => $packages->getUrl($path, $packageName),
			'asset_version' => static fn (string $path, ?string $packageName = NULL): string => $packages->getVersion($path, $packageName),
		];
	}
}
