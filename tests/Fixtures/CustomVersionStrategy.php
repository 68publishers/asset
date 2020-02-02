<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Tests\Fixtures;

use Symfony;

final class CustomVersionStrategy implements Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface
{
	/** @var string  */
	private $postfix;

	/**
	 * @param string $postfix
	 */
	public function __construct(string $postfix)
	{
		$this->postfix = $postfix;
	}

	/********************* interface \Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface *********************/

	/**
	 * {@inheritdoc}
	 */
	public function getVersion($path): string
	{
		return $this->postfix;
	}

	/**
	 * {@inheritdoc}
	 */
	public function applyVersion($path): string
	{
		return $path . $this->postfix;
	}
}
