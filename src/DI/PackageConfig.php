<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette\DI\Definitions\Statement;

class PackageConfig
{
	public ?string $base_path;

	/** @var array<string> */
	public array $base_urls;

	public ?string $version;

	public ?string $version_format;

	public ?Statement $version_strategy;

	public ?string $json_manifest_path;
}
