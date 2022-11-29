<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Latte;

use Latte\Extension;
use Symfony\Component\Asset\Packages;
use SixtyEightPublishers\Asset\Bridge\Latte\Node\AssetNode;
use SixtyEightPublishers\Asset\Bridge\Latte\Node\AssetVersionNode;

final class AssetLatte3Extension extends Extension
{
	private Packages $packages;

	public function __construct(Packages $packages)
	{
		$this->packages = $packages;
	}

	/**
	 * @return array{asset: callable, asset_version: callable}
	 */
	public function getTags(): array
	{
		return [
			'asset' => [AssetNode::class, 'create'],
			'asset_version' => [AssetVersionNode::class, 'create'],
		];
	}

	public function getFunctions(): array
	{
		return AssetFunctionSet::functions($this->packages);
	}

	public function getProviders(): array
	{
		return AssetProviderSet::providers($this->packages);
	}
}
