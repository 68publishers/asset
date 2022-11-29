<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Latte;

use Symfony\Component\Asset\Packages;

/**
 * @internal
 */
final class AssetProviderSet
{
	private function __construct()
	{
	}

	/**
	 * @return array{symfonyPackages: Packages}
	 */
	public static function providers(Packages $packages): array
	{
		return [
			'symfonyPackages' => $packages,
		];
	}
}
