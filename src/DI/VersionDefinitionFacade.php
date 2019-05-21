<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette;
use Symfony;

final class VersionDefinitionFacade
{
	use Nette\SmartObject;

	/** @var \SixtyEightPublishers\Asset\DI\ReferenceFacade  */
	private $referenceFacade;

	/**
	 * @param \SixtyEightPublishers\Asset\DI\ReferenceFacade $referenceFacade
	 */
	public function __construct(ReferenceFacade $referenceFacade)
	{
		$this->referenceFacade = $referenceFacade;
	}

	/**
	 * @param string      $name
	 * @param string|NULL $version
	 * @param string      $format
	 * @param string|NULL $jsonManifestPath
	 *
	 * @return \Nette\DI\Statement
	 */
	public function createVersionStatement(string $name, ?string $version, string $format, ?string $jsonManifestPath): Nette\DI\Statement
	{
		// Configuration prevents $version and $jsonManifestPath from being set
		if (NULL !== $version) {
			$reference = $this->getVersionDependencyReference(
				new Nette\DI\Statement(Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy::class, [
					'version' => $version,
					'format' => $format,
				]),
				$name
			);
		}

		if (NULL !== $jsonManifestPath) {
			$reference = $this->getVersionDependencyReference(
				new Nette\DI\Statement(Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy::class, [
					'manifestPath' => $jsonManifestPath,
				]),
				$name
			);
		}

		return new Nette\DI\Statement(
			$reference ?? $this->getVersionDependencyReference(Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy::class, $name)
		);
	}

	/**
	 * @param string|\Nette\DI\Statement $definition
	 * @param string                     $versionName
	 *
	 * @return string
	 */
	public function getVersionDependencyReference($definition, string $versionName): string
	{
		return $this->referenceFacade->getDependencyReference(
			$definition,
			'version_strategy.' . $versionName,
			Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface::class
		);
	}
}
