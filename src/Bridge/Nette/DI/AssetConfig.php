<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Nette\DI;

final class AssetConfig extends PackageConfig
{
	/** @var array<string, PackageConfig> */
	public array $packages = [];
}
