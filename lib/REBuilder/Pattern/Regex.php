<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Pattern;

/**
 * Represents the main regex container and will contain the entire
 * regex structure
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class Regex extends AbstractContainer
{
    /**
     * Start delimiter
     * 
     * @var string
     */
    protected $_startDelimiter;

    /**
     * End delimiter
     * 
     * @var string
     */
    protected $_endDelimiter;

    /**
     * Modifiers
     * 
     * @var string
     */
    protected $_modifiers;

    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;

    /**
     * Constructor
     * 
     * @param string $modifiers Regex modifiers
     * @param string $delimiter Regex delimiter
     */
    public function __construct ($modifiers = "", $delimiter = "/")
    {
        $this->setModifiers($modifiers);
        $this->setDelimiter($delimiter);
    }

    /**
     * Sets the regex delimiter
     * 
     * @param string $delimiter Regex delimiter
     * 
     * @return Regex
     * 
     * @throws \REBuilder\Exception\InvalidDelimiter
     */
    public function setDelimiter ($delimiter)
    {
        if (!\REBuilder\Parser\Rules::validateDelimiter($delimiter)) {
            throw new \REBuilder\Exception\InvalidDelimiter(
                "Invalid delimiter '$delimiter'"
            );
        }
        $this->_startDelimiter = $delimiter;
        $this->_endDelimiter = \REBuilder\Parser\Rules::getEndDelimiter($delimiter);
        return $this;
    }

    /**
     * Returns the regex start delimiter
     * 
     * @return string
     */
    public function getStartDelimiter ()
    {
        return $this->_startDelimiter;
    }

    /**
     * Returns the regex end delimiter
     * 
     * @return string
     */
    public function getEndDelimiter ()
    {
        return $this->_endDelimiter;
    }

    /**
     * Sets regex modifiers
     * 
     * @param string $modifiers Regex modifiers
     * 
     * @return Regex
     * 
     * @throws \REBuilder\Exception\InvalidModifier
     */
    public function setModifiers ($modifiers)
    {
        if (!\REBuilder\Parser\Rules::validateModifiers(
                $modifiers, $wrongModifier
            )) {
            throw new \REBuilder\Exception\InvalidModifier(
                "Invalid modifier '$wrongModifier'"
            );
        }
        $this->_modifiers = $modifiers;
        return $this;
    }

    /**
     * Returns the regex modifiers
     * 
     * @return string
     */
    public function getModifiers ()
    {
        return $this->_modifiers;
    }

    /**
     * Quotes the given string using current configurations
     * 
     * @param string $str String to quote
     * 
     * @return string
     */
    public function quote ($str)
    {
        return preg_quote($str, $this->_startDelimiter);
    }
    
    /**
     * Test if the regex matches the given string
     * 
     * @param string $str Test string
     * 
     * @return bool
     */
    public function test ($str)
    {
        return preg_match($this->render(), $str) === 1;
    }
    
    /**
     * Executes the regex on the given string and return the matches array or
     * null if the string does not match
     * 
     * @param string $str           The string to match
     * @param bool   $setOrder      True to group the matches in sets
     * @param bool   $captureOffset True to capture matches offset too
     * 
     * @return array|null
     */
    public function exec ($str, $setOrder = false, $captureOffset = false)
    {
        if ($setOrder) {
            $flags = PREG_SET_ORDER;
        } else {
            $flags = PREG_PATTERN_ORDER;
        }
        if ($captureOffset) {
            $flags = $flags | PREG_OFFSET_CAPTURE;
        }
        if (preg_match_all($this->render(), $str, $matches, $flags)) {
            return $matches;
        }
        return null;
    }
    
    /**
     * Filters an array by removing values that do not match the regex
     * 
     * @param array $array  Array to filter
     * @param bool  $invert If true the behaviour is inverted and this function
     *                      filters out values that match the regex
     * 
     * @return array
     */
    public function grep ($array, $invert = false)
    {
        $flags = $invert ? PREG_GREP_INVERT : 0;
        return preg_grep($this->render(), $array, $flags);
    }
    
    /**
     * Splits the given string using the regex
     * 
     * @param string $str           The string to split
     * @param int    $limit         Maximum number of substrings to return
     * @param bool   $noEmpty       If true only non empty substrings are
     *                              returned
     * @param bool   $captureDelim  If true also capturing pattern in the
     *                              delimiter are returned
     * @param bool   $captureOffset If true the offset of each substring is
     *                              returned
     * 
     * @return array
     */
    public function split ($str, $limit = null, $noEmpty = false,
                           $captureDelim = false, $captureOffset = false)
    {
        $flags = 0;
        if ($noEmpty) {
            $flags = $flags | PREG_SPLIT_NO_EMPTY;
        }
        if ($captureDelim) {
            $flags = $flags | PREG_SPLIT_DELIM_CAPTURE;
        }
        if ($captureOffset) {
            $flags = $flags | PREG_SPLIT_OFFSET_CAPTURE;
        }
        if ($limit === null) {
            $limit = -1;
        }
        return preg_split($this->render(), $str, $limit, $flags);
    }
    
    /**
     * Replaces the matched pattern with the given replacement
     * 
     * @param string|callable $replace Replacement string or callback that will
     *                                 will be executed on every pattern match
     *                                 to get the replacement
     * @param string          $str     Subject string
     * @param int             $limit   Maximum number of replacements
     * 
     * @return string
     */
    public function replace ($replace, $str, $limit = null)
    {
        if ($limit === null) {
            $limit = -1;
        }
        $fn = is_callable($replace) ? "preg_replace_callback" : "preg_replace";
        return $fn($this->render(), $replace, $str, $limit);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return $this->getStartDelimiter() .
               $this->renderChildren() .
               $this->getEndDelimiter() .
               $this->getModifiers();
    }
}