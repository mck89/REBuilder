<?php
/**
 * Represent lookahead and lookbehind assertions
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.assertions.php
 */
class REBuilder_Pattern_Assertion extends REBuilder_Pattern_AbstractContainer
{
    /**
     * True if the assertion is a lookahead assertion, false if it's a
     * lookbehind assertion
     * 
     * @var bool
     */
    protected $_lookahead = true;

    /**
     * Flag that indicates if it's a negative assertion
     * 
     * @var bool
     */
    protected $_negate = false;

    /**
     * Constructor
     * 
     * @param bool $lookahead True to create a lookahead assertion, false to
     *                        create a lookbehind assertion
     * @param bool $negate    True to make a negative assertion
     */
    public function __construct ($lookahead = true, $negate = false)
    {
        $this->setLookahead($lookahead);
        $this->setNegate($negate);
    }

    /**
     * Set the assertion type. True to create a lookahead assertion, false to
     * create a lookbehind assertion
     * 
     * @param bool $lookahead Assertion type
     * @return REBuilder_Pattern_Assertion
     */
    public function setLookahead ($lookahead)
    {
        $this->_lookahead = (bool) $lookahead;
        return $this;
    }

    /**
     * Returns true if the assertion is a lookahead assertion, false if it's a
     * lookbehind assertion
     * 
     * @return bool
     */
    public function getLookahead ()
    {
        return $this->_lookahead;
    }

    /**
     * Set the negation flag. If the parameter is true the assertion will be a
     * negative assertion
     * 
     * @param bool $negate Negation flag
     * @return REBuilder_Pattern_Assertion
     */
    public function setNegate ($negate)
    {
        $this->_negate = (bool) $negate;
        return $this;
    }

    /**
     * Returns true if it's a negative assertion, otherwise false
     * 
     * @return bool
     */
    public function getNegate ()
    {
        return $this->_negate;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        $ret = "(?";
        if (!$this->getLookahead()) {
            $ret .= "<";
        }
        if ($this->getNegate()) {
            $ret .= "!";
        } else {
            $ret .= "=";
        }
        $ret .= $this->renderChildren();
        $ret .= ")";
        //Assertions support repetition but they make no sense so they won't be
        //rendered
        return $ret;
    }
}