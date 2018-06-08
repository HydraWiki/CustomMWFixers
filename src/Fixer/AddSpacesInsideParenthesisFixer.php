<?php

namespace HydraCustomFixers\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\Token;

/**
 * Fixer for rules for MediaWiki.
 *
 * @author Samuel Hilson
 */
final class AddSpacesInsideParenthesisFixer implements DefinedFixerInterface
{
    public function getDefinition()
    {
        return new FixerDefinition(
            'There MUST be a space after the opening parenthesis. There MUST be a space before the closing parenthesis.',
            [
                new CodeSample("<?php\nif (\$a) {\n    foo();\n}\n"),
                new CodeSample(
                    "<?php
function foo(\$bar, \$baz)
{
}\n"
                ),
            ]
        );
    }

    public function isRisky()
    {
        return false;
    }

    public function getName()
    {
        return 'HydraCustomFixers/add_spaces_inside_parenthesis';
    }

    public function supports(\SplFileInfo $file)
    {
        return true;
    }

    public function getPriority()
    {
        return 0;
    }

    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isAnyTokenKindsFound(['(', '[', T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN]);
    }

    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->equalsAny(['(', '['])) {
                continue;
            }

            $opening = $token->getContent();
            $closing = $token->equals('(') ? ')' : ']';
            $type = Tokens::detectBlockType($token)['type'];
            $endIndex = $tokens->findBlockEnd($type, $index);
            $charAfterParenStart = $tokens[$index + 1];
            $charBeforeParenEnd = $tokens[$endIndex -1];

            // add space after opening `(` or `[`
            if (!$charAfterParenStart->isWhiteSpace() &&
            !$charAfterParenStart->isComment() &&
            !$charAfterParenStart->equals($closing)) {
                $this->addSpaceAroundToken($tokens, $index + 1);
            }
            // add space before closing `)` or `]`
            if (!$charBeforeParenEnd->isWhiteSpace() && !$charBeforeParenEnd->equals($opening)) {
                $this->addSpaceAroundToken($tokens, $endIndex + 1);
            }
        }
    }

    /**
     * Adds spaces to token at a given index.
     *
     * @param Tokens $tokens
     * @param int    $index
     */
    private function addSpaceAroundToken(Tokens $tokens, $index)
    {
        $token = $tokens[$index];
        if (false === strpos($token->getContent(), "\n")) {
            $tokens->insertAt($index, new Token([T_WHITESPACE, ' ']));
        }
    }
}
