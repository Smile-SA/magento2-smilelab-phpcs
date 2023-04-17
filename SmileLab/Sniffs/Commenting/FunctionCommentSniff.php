<?php

declare(strict_types=1);

namespace SmileLab\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SmileLab\Helpers\SniffSettingsHelper;

/**
 * Sniff to assert that methods have a doc block.
 */
class FunctionCommentSniff implements Sniff
{
    private array $validTokensBeforeClosingCommentTag = [
        'T_WHITESPACE',
        'T_PUBLIC',
        'T_PRIVATE',
        'T_PROTECTED',
        'T_STATIC',
        'T_ABSTRACT',
        'T_FINAL',
    ];

    private ?bool $enabledOnAnnotations = null;

    /**
     * @inheritdoc
     */
    public function register(): array
    {
        return [
            T_FUNCTION,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $previousCommentOpenPtr = $phpcsFile->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr - 1, 0);
        $previousCommentClosePtr = $phpcsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1, 0);
        $functionPtrContent = $tokens[$stackPtr + 2]['content'];
        if ($previousCommentClosePtr && $previousCommentOpenPtr) {
            if (!$this->validateCommentBlockExists($phpcsFile, $previousCommentClosePtr, $stackPtr)) {
                if (preg_match('/(?i)__construct|__destruct/', $functionPtrContent)) {
                    return;
                }
                $phpcsFile->addError('Comment block is missing', $stackPtr, 'NoCommentBlock');
            }
        }
    }

    /**
     * Validates whether comment block exists.
     */
    private function validateCommentBlockExists(File $phpcsFile, int $previousCommentClosePtr, int $stackPtr): bool
    {
        $tokens = $phpcsFile->getTokens();
        $attributeFlag = false;
        for ($tempPtr = $previousCommentClosePtr + 1; $tempPtr < $stackPtr; $tempPtr++) {
            $tokenCode = $tokens[$tempPtr]['code'];

            $this->enabledOnAnnotations = SniffSettingsHelper::isEnabledByPhpVersion(
                $this->enabledOnAnnotations,
                80000
            );

            if ($this->enabledOnAnnotations) {
                // PHP >= 8.0: Ignore attributes e.g. #[\ReturnTypeWillChange]
                if ($tokenCode === T_ATTRIBUTE_END) {
                    $attributeFlag = false;
                    continue;
                }
                if ($attributeFlag) {
                    continue;
                }
                if ($tokenCode === T_ATTRIBUTE) {
                    $attributeFlag = true;
                    continue;
                }
            }

            if (!in_array($tokens[$tempPtr]['type'], $this->validTokensBeforeClosingCommentTag, true)) {
                return false;
            }
        }

        return true;
    }
}
