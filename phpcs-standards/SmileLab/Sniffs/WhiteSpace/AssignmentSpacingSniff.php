<?php
use PHP_CodeSniffer\Util\Tokens;
/**
 * Check whitespace during assignments.
 *
 * @category  SmileLab
 * @package   SmileLab\CodingStandards
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
class SmileLab_Sniffs_WhiteSpace_AssignmentSpacingSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = ['PHP'];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return Tokens::$assignmentTokens;

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile All the tokens found in the document.
     * @param int                         $stackPtr  The position of the current token in
     *                                               the stack passed in $tokens.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr -1]['code'] !== T_WHITESPACE || $tokens[$stackPtr +1]['code'] !== T_WHITESPACE) {
            $phpcsFile->addError(
                'Add a single space around assignment operators',
                $stackPtr,
                'Invalid'
            );
        }
    }//end process()

}//end class
