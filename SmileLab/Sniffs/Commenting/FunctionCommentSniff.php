<?php

declare(strict_types=1);

namespace SmileLab\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Sniff to assert that methods have a doc block.
 */
class FunctionCommentSniff implements Sniff
{
    /**
     * Array of methods which do not require a return type.
     */
    public array $specialMethods = [
        '__construct',
        '__destruct',
    ];

    private bool $enabledOnAnnotations;

    public function __construct()
    {
        // Backwards-compatibility with Magento <2.4.4 where codesniffer doesn't define this constant
        $this->enabledOnAnnotations = defined('T_ATTRIBUTE_END');
    }

    /**
     * @inheritdoc
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        // Skip constructor and destructor
        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if (in_array($methodName, $this->specialMethods, true)) {
            return;
        }

        $ignore = Tokens::$methodPrefixes;
        $ignore[T_WHITESPACE] = T_WHITESPACE;

        for ($commentEnd = $stackPtr - 1; $commentEnd >= 0; $commentEnd--) {
            if (isset($ignore[$tokens[$commentEnd]['code']]) === true) {
                continue;
            }

            if (
                $this->enabledOnAnnotations
                && $tokens[$commentEnd]['code'] === T_ATTRIBUTE_END
                && isset($tokens[$commentEnd]['attribute_opener']) === true
            ) {
                $commentEnd = $tokens[$commentEnd]['attribute_opener'];
                continue;
            }

            break;
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            // Inline comments might just be closing comments for
            // control structures or functions instead of function comments
            // using the wrong comment type. If there is other code on the line,
            // assume they relate to that code.
            $prev = $phpcsFile->findPrevious($ignore, $commentEnd - 1, null, true);
            if ($prev !== false && $tokens[$prev]['line'] === $tokens[$commentEnd]['line']) {
                $commentEnd = $prev;
            }
        }

        if (
            $tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            $function = $phpcsFile->getDeclarationName($stackPtr);
            $phpcsFile->addError(
                'Missing doc comment for function %s()',
                $stackPtr,
                'Missing',
                [$function]
            );
            $phpcsFile->recordMetric($stackPtr, 'Function has doc comment', 'no');
            return;
        } else {
            $phpcsFile->recordMetric($stackPtr, 'Function has doc comment', 'yes');
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError('You must use "/**" style comments for a function comment', $stackPtr, 'WrongStyle');
            return;
        }

        if ($tokens[$commentEnd]['line'] !== $tokens[$stackPtr]['line'] - 1) {
            for ($i = $commentEnd + 1; $i < $stackPtr; $i++) {
                if ($tokens[$i]['column'] !== 1) {
                    continue;
                }

                if (
                    $tokens[$i]['code'] === T_WHITESPACE
                    && $tokens[$i]['line'] !== $tokens[$i + 1]['line']
                ) {
                    $error = 'There must be no blank lines after the function comment';
                    $phpcsFile->addError($error, $commentEnd, 'SpacingAfter');
                    break;
                }
            }
        }
    }
}
