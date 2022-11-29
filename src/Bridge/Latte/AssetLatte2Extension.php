<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Latte;

use Latte\Engine;
use Symfony\Component\Asset\Packages;

final class AssetLatte2Extension
{
	private function __construct()
	{
	}

	public static function extend(Engine $engine, Packages $packages): void
	{
		foreach (AssetProviderSet::providers($packages) as $providerName => $provider) {
			$engine->addProvider($providerName, $provider);
		}

		foreach (AssetFunctionSet::functions($packages) as $functionName => $functionCallback) {
			$engine->addFunction($functionName, $functionCallback);
		}

		$engine->onCompile[] = static function (Engine $engine): void {
			AssetMacroSet::install($engine->getCompiler());
		};
	}
}
