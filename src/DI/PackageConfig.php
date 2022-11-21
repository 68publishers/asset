<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette\DI\Definitions\Statement;

class PackageConfig
{
	/** @var string|Statement|null */
	public $base_path;

	/** @var array<string|Statement> */
	public array $base_urls;

	public ?string $version;

	public ?string $version_format;

	public ?Statement $version_strategy;

	public ?string $json_manifest_path;
}
