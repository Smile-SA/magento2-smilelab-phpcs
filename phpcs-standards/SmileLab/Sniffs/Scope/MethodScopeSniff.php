<?php

use PHP_CodeSniffer\Util\Tokens;

/**
 * Check all methods have a scope.
 *
 * @category  SmileLab
 * @package   SmileLab\CodingStandards
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
class SmileLab_Sniffs_Scope_MethodScopeSniff extends \PHP_CodeSniffer\Sniffs\AbstractScopeSniff
{
    /**
     * Constructs a Symfony2_Sniffs_Scope_MethodScopeSniff.
     *
     * @throws \PHP_CodeSniffer\Exceptions\RuntimeException
     */
    public function __construct()
    {
        parent::__construct(Tokens::$ooScopeTokens, [T_FUNCTION]);

    }//end __construct()

    /**
     * Processes the function tokens within the class.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position where the token was found.
     * @param int                         $currScope The current scope opener token.
     *
     * @return void
     * @throws \PHP_CodeSniffer\Exceptions\RuntimeException
     */
    protected function processTokenWithinScope(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr, $currScope)
    {
        $tokens = $phpcsFile->getTokens();

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        $modifier = $phpcsFile->findPrevious(Tokens::$scopeModifiers, $stackPtr);
        if (($modifier === false) || ($tokens[$modifier]['line'] !== $tokens[$stackPtr]['line'])) {
            $error = 'No scope modifier specified for function "%s"';
            $data  = [$methodName];
            $phpcsFile->addError($error, $stackPtr, 'Missing', $data);
        }

    }//end processTokenWithinScope()

    /**
     * Processes a token that is found within the scope that this test is
     * listening to.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position in the stack where this
     *                                               token was found.
     *
     * @return void
     */
    protected function processTokenOutsideScope(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {

    }//end processTokenOutsideScope()
}//end class
