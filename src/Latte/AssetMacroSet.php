<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Latte;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\Macros\MacroSet;

final class AssetMacroSet extends MacroSet
{
	public static function install(Compiler $compiler): void
	{
		$me = new self($compiler);

		$me->addMacro('asset', [$me, 'macroAsset']);
		$me->addMacro('asset_version', [$me, 'macroAssetVersion']);
	}

	/**
	 * @throws \Latte\CompileException
	 */
	public function macroAsset(MacroNode $node, PhpWriter $writer): string
	{
		return $writer->write('echo %escape(%modify($this->global->symfonyPackages->getUrl(%node.args)))');
	}

	/**
	 * @throws \Latte\CompileException
	 */
	public function macroAssetVersion(MacroNode $node, PhpWriter $writer): string
	{
		return $writer->write('echo %escape(%modify($this->global->symfonyPackages->getVersion(%node.args)))');
	}
}
