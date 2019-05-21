<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Latte;

use Latte;

class AssetMacroSet extends Latte\Macros\MacroSet
{
	/**
	 * @param \Latte\Compiler $compiler
	 *
	 * @return void
	 */
	public static function install(Latte\Compiler $compiler): void
	{
		$me = new static($compiler);

		$me->addMacro('asset', [$me, 'macroAsset']);
		$me->addMacro('asset_version', [$me, 'macroAssetVersion']);
	}

	/**
	 * @param \Latte\MacroNode $node
	 * @param \Latte\PhpWriter $writer
	 *
	 * @return string
	 */
	public function macroAsset(Latte\MacroNode $node, Latte\PhpWriter $writer): string
	{
		return $writer->write('echo %escape(%modify($this->global->symfonyPackages->getUrl(%node.args)))');
	}

	/**
	 * @param \Latte\MacroNode $node
	 * @param \Latte\PhpWriter $writer
	 *
	 * @return string
	 */
	public function macroAssetVersion(Latte\MacroNode $node, Latte\PhpWriter $writer): string
	{
		return $writer->write('echo %escape(%modify($this->global->symfonyPackages->getVersion(%node.args)))');
	}
}
