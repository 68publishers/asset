<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Asset\Bridge\Latte\Node;

use Generator;
use Latte\Compiler\Tag;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;

/**
 * {asset, path [, packageName]}
 */
final class AssetNode extends StatementNode
{
	public ExpressionNode $path;

	public ?ExpressionNode $packageName = NULL;

	/**
	 * @throws \Latte\CompileException
	 */
	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		$node = new self;
		$node->path = $tag->parser->parseUnquotedStringOrExpression();

		if ($tag->parser->stream->tryConsume(',')) {
			$node->packageName = $tag->parser->parseUnquotedStringOrExpression();
		}

		return $node;
	}

	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo %escape($this->global->symfonyPackages->getUrl(%node, %node?)) %line;',
			$this->path,
			$this->packageName,
			$this->position,
		);
	}

	public function &getIterator(): Generator
	{
		yield $this->path;

		if (NULL !== $this->packageName) {
			yield $this->packageName;
		}
	}
}
