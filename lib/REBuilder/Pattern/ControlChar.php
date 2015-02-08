<?php
/**
 * Represents the control character identifier \c
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_ControlChar extends REBuilder_Pattern_Char
{
	/**
	 * Sets the character to match. It can be any character
	 * 
	 * @param string $char Character to match
	 * @return REBuilder_Pattern_ControlChar
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setChar ($char)
	{
		$char = "$char";
		if (strlen($char) !== 1) {
			throw new REBuilder_Exception_Generic(
				"Control character requires a single character"
			);
		}
		return parent::setChar($char);
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		if ($this->_char === null || $this->_char === "") {
			throw new REBuilder_Exception_Generic(
				"No character has been set"
			);
		}
		return "\c" . $this->_char . $this->_renderRepetition();
	}
}