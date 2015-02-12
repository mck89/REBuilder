<?php
/**
 * Represents a character or a group of characters that will be matched as they
 * are, like "a" and "bc" in /a.bc/
 * 
 * @author Marco Marchiò
 * @abstract
 */
class REBuilder_Pattern_Char extends REBuilder_Pattern_AbstractChar
{
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		$char = parent::render();
		$needsGroup = strlen($char) > 1 && $this->getRepetition();
		$char = $this->getParentRegex()->quote($char);
		return ($needsGroup ? "(?:$char)" : $char) . $this->_renderRepetition();
	}
}