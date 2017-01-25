<?php
/**
 * Check binary operator whitespace.
 *
 * @category  SmileLab
 * @package   SmileLab\CodingStandards
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 SmileLab
 * @license   Open Software License ("OSL") v. 3.0
 */
class SmileLab_Sniffs_WhiteSpace_BinaryOperatorSpacingSniff
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
        return PHP_CodeSniffer_Tokens::$comparisonTokens;

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr -1]['code'] !== T_WHITESPACE || $tokens[$stackPtr +1]['code'] !== T_WHITESPACE) {
            $phpcsFile->addError(
                'Add a single space around binary operators',
                $stackPtr,
                'Invalid'
            );
        }
    }//end process()

}//end class
