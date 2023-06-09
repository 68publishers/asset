<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\VersionStrategy;

final class CacheAssets
{
	private bool $debugMode;

	private string $tempFile;

	/** @var ?array<int> */
	private ?array $files = null;

	private bool $save = false;


	public function __construct(bool $debugMode, string $tempDir)
	{
		$this->debugMode = $debugMode;
		$this->tempFile = $tempDir . '/_assets.php';
	}


	public function load(string $pathname): int
	{
		$this->loadCache();
		if (isset($this->files[$pathname])) {
			return $this->files[$pathname];
		}

		$this->save = true;
		$mtime = filemtime($pathname);

		return $this->files[$pathname] = $mtime === false ? 0 : $mtime;
	}


	private function loadCache(): void
	{
		if ($this->files !== null) {
			return;
		} elseif ($this->debugMode === true) {
			$this->files = [];
		} else {
			$this->files = require $this->tempFile;
		}
	}


	/**
	 * Clear local cache
	 * @return static
	 */
	public function clear()
	{
		$this->files = [];
		$this->save = true;

		return $this;
	}


	public function __destruct()
	{
		if ($this->debugMode === false && $this->save === true) {
			file_put_contents($this->tempFile, '<?php return ' . var_export($this->files, true) . ';');
		}
	}

}
