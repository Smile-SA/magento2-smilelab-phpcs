<?php
use PHP_CodeSniffer\Util\Tokens;

/**
 * Method declaration sniff.
 *
 * @category  SmileLab
 * @package   SmileLab\CodingStandards
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
class SmileLab_Sniffs_Methods_MethodDeclarationSniff extends \PHP_CodeSniffer\Sniffs\AbstractScopeSniff
{
    /**
     * Constructs a SmileLab_Sniffs_Methods_MethodDeclarationSniff.
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

        $visibility = 0;
        $static     = 0;
        $abstract   = 0;
        $final      = 0;

        $find   = Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;
        $prev   = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

        $prefix = $stackPtr;
        while (($prefix = $phpcsFile->findPrevious(Tokens::$methodPrefixes, ($prefix - 1), $prev)) !== false) {
            switch ($tokens[$prefix]['code']) {
            case T_STATIC:
                $static = $prefix;
                break;
            case T_ABSTRACT:
                $abstract = $prefix;
                break;
            case T_FINAL:
                $final = $prefix;
                break;
            default:
                $visibility = $prefix;
                break;
            }
        }

        $fixes = array();

        if ($visibility !== 0 && $final > $visibility) {
            $error = 'The final declaration must precede the visibility declaration';
            $fix   = $phpcsFile->addFixableError($error, $final, 'FinalAfterVisibility');
            if ($fix === true) {
                $fixes[$final]       = '';
                $fixes[($final + 1)] = '';
                if (isset($fixes[$visibility]) === true) {
                    $fixes[$visibility] = 'final '.$fixes[$visibility];
                } else {
                    $fixes[$visibility] = 'final '.$tokens[$visibility]['content'];
                }
            }
        }

        if ($visibility !== 0 && $abstract > $visibility) {
            $error = 'The abstract declaration must precede the visibility declaration';
            $fix   = $phpcsFile->addFixableError($error, $abstract, 'AbstractAfterVisibility');
            if ($fix === true) {
                $fixes[$abstract]       = '';
                $fixes[($abstract + 1)] = '';
                if (isset($fixes[$visibility]) === true) {
                    $fixes[$visibility] = 'abstract '.$fixes[$visibility];
                } else {
                    $fixes[$visibility] = 'abstract '.$tokens[$visibility]['content'];
                }
            }
        }

        if ($static !== 0 && $static < $visibility) {
            $error = 'The static declaration must come after the visibility declaration';
            $fix   = $phpcsFile->addFixableError($error, $static, 'StaticBeforeVisibility');
            if ($fix === true) {
                $fixes[$static]       = '';
                $fixes[($static + 1)] = '';
                if (isset($fixes[$visibility]) === true) {
                    $fixes[$visibility] = $fixes[$visibility].' static';
                } else {
                    $fixes[$visibility] = $tokens[$visibility]['content'].' static';
                }
            }
        }

        // Batch all the fixes together to reduce the possibility of conflicts.
        if (empty($fixes) === false) {
            $phpcsFile->fixer->beginChangeset();
            foreach ($fixes as $stackPtr => $content) {
                $phpcsFile->fixer->replaceToken($stackPtr, $content);
            }

            $phpcsFile->fixer->endChangeset();
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
