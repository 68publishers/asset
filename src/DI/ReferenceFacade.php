<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\DI;

use Nette;

final class ReferenceFacade
{
	use Nette\SmartObject;

	/** @var \Nette\DI\CompilerExtension  */
	private $extension;

	/**
	 * @param \Nette\DI\CompilerExtension $extension
	 */
	public function __construct(Nette\DI\CompilerExtension $extension)
	{
		$this->extension = $extension;
	}

	/**
	 * @param string|\Nette\DI\Statement $definition
	 * @param string                     $registrationName
	 * @param string                     $type
	 *
	 * @return string
	 */
	public function getDependencyReference($definition, string $registrationName, string $type): string
	{
		if (!is_string($definition) || !Nette\Utils\Strings::startsWith($definition, '@')) {
			$this->extension
				->getContainerBuilder()
				->addDefinition($registrationName = $this->extension->prefix($registrationName))
				->setType($type)
				->setFactory($definition)
				->setInject(FALSE);

			$registrationName = '@' . $registrationName;
		} else {
			$registrationName = $definition;
		}

		return $registrationName;
	}
}
