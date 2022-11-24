<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Latte;

use Latte\Extension;
use Latte\Compiler\Tag;
use Latte\Compiler\Node;
use Latte\Compiler\PrintContext;
use Symfony\Component\Asset\Packages;
use Latte\Compiler\Nodes\AuxiliaryNode;

final class AssetExtension extends Extension
{
	private Packages $packages;

	public function __construct(Packages $packages)
	{
		$this->packages = $packages;
	}

	/**
	 * @return array{asset: callable, asset_version: callable}
	 */
	public function getTags(): array
	{
		return [
			'asset' => [$this, 'createAsset'],
			'asset_version' => [$this, 'createAssetVersion'],
		];
	}

	/**
	 * @return array{asset: callable, asset_version: callable}
	 */
	public function getFunctions(): array
	{
		return [
			'asset' => [$this->packages, 'getUrl'],
			'asset_version' => [$this->packages, 'getVersion'],
		];
	}

	/**
	 * @return array{symfonyPackages: Packages}
	 */
	public function getProviders(): array
	{
		return [
			'symfonyPackages' => $this->packages,
		];
	}

	public function createAsset(Tag $tag): Node
	{
		$args = [];
		$path = $tag->parser->parseUnquotedStringOrExpression();

		if (!$tag->parser->isEnd()) {
			$tag->parser->stream->tryConsume(',');
			$args = [$tag->parser->parseExpression()];
		}

		return new AuxiliaryNode(
			fn (PrintContext $context) => $context->format(
				'echo %escape($this->global->symfonyPackages->getUrl(%node, %args));',
				$path,
				$args
			)
		);
	}

	public function createAssetVersion(Tag $tag): Node
	{
		$args = [];
		$path = $tag->parser->parseUnquotedStringOrExpression();

		if (!$tag->parser->isEnd()) {
			$tag->parser->stream->tryConsume(',');
			$args = [$tag->parser->parseExpression()];
		}

		return new AuxiliaryNode(
			fn (PrintContext $context) => $context->format(
				'echo %escape($this->global->symfonyPackages->getVersion(%node, %args));',
				$path,
				$args
			)
		);
	}
}
