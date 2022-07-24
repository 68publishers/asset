<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette;
use Symfony;

final class PackageDefinitionFacade
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
	 * @param string                          $name
	 * @param string|NULL|\Nette\DI\Statement $basePath
	 * @param array                           $baseUrls
	 * @param \Nette\DI\Statement             $versionStrategy
	 *
	 * @return \Nette\DI\Statement
	 */
	public function createPackageStatement(string $name, $basePath, array $baseUrls, Nette\DI\Statement $versionStrategy): Nette\DI\Statement
	{
		if (!empty($basePath) && !empty($baseUrls)) {
			throw new \LogicException('An asset package cannot have base URLs and base paths.');
		}

		if (empty($baseUrls)) {
			$reference = $this->getPackageDependencyReference(
				new Nette\DI\Statement(Symfony\Component\Asset\PathPackage::class, [
					'basePath' => $basePath ?? '',
					'versionStrategy' => $versionStrategy,
				]),
				$name
			);
		} else {
			$reference = $this->getPackageDependencyReference(
				new Nette\DI\Statement(Symfony\Component\Asset\UrlPackage::class, [
					'baseUrls' => $baseUrls,
					'versionStrategy' => $versionStrategy,
				]),
				$name
			);
		}

		return new Nette\DI\Statement($reference);
	}

	/**
	 * @param string|\Nette\DI\Statement $definition
	 * @param string                     $packageName
	 *
	 * @return string
	 */
	public function getPackageDependencyReference($definition, string $packageName): string
	{
		return $this->referenceFacade->getDependencyReference(
			$definition,
			'package.' . $packageName,
			Symfony\Component\Asset\PackageInterface::class
		);
	}
}
