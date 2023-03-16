<?php declare(strict_types=1);

namespace SixtyEightPublishers\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

final class FileModifyTimeStrategy implements VersionStrategyInterface
{
	private string $wwwDir;

	private CacheAssets $cacheAssets;


	public function __construct(string $wwwDir, CacheAssets $cacheAssets)
	{
		$this->wwwDir = $wwwDir;
		$this->cacheAssets = $cacheAssets;
	}


	public function getVersion(string $path): string
	{
		return (string) $this->cacheAssets->load($this->absolutePath($path));
	}


	public function applyVersion(string $path): string
	{
		return "$path?" . $this->getVersion($path);
	}


	private function absolutePath(string $path): string
	{
		return "$this->wwwDir/" . ltrim($path, '/');
	}

}
