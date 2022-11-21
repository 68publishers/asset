<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Fixtures;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

final class CustomVersionStrategy implements VersionStrategyInterface
{
	private string $postfix;

	public function __construct(string $postfix)
	{
		$this->postfix = $postfix;
	}

	public function getVersion($path): string
	{
		return $this->postfix;
	}

	public function applyVersion($path): string
	{
		return $path . $this->postfix;
	}
}
