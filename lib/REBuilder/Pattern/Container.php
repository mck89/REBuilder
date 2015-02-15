<?php
/**
 * Abstract class for classes that can contain child patterns
 * 
 * @author Marco Marchiò
 * @abstract
 * @method REBuilder_Pattern_Char addChar()
 *		   addChar(string $char)
 *		   Adds a new REBuilder_Pattern_Char class instance to this container
 *		   @see REBuilder_Pattern_Char::__construct
 * 
 * @method REBuilder_Pattern_Container addCharAndContinue()
 *		   addCharAndContinue(string $char)
 *		   Same as addChar but it returns the current container
 *		   @see REBuilder_Pattern_Char::__construct
 * 
 * @method REBuilder_Pattern_ControlChar addControlChar()
 *		   addControlChar(string $char)
 *		   Adds a new REBuilder_Pattern_ControlChar class instance to this container
 *		   @see REBuilder_Pattern_ControlChar::__construct
 * 
 * @method REBuilder_Pattern_Container addControlCharAndContinue()
 *		   addControlCharAndContinue(string $char)
 *		   Same as addControlChar but it returns the current container
 *		   @see REBuilder_Pattern_ControlChar::__construct
 * 
 * @method REBuilder_Pattern_HexChar addHexChar()
 *		   addHexChar(string $char)
 *		   Adds a new REBuilder_Pattern_HexChar class instance to this container
 *		   @see REBuilder_Pattern_HexChar::__construct
 * 
 * @method REBuilder_Pattern_Container addHexCharAndContinue()
 *		   addHexCharAndContinue(string $char)
 *		   Same as addHexChar but it returns the current container
 *		   @see REBuilder_Pattern_HexChar::__construct
 * 
 * @method REBuilder_Pattern_GenericCharType addGenericCharType()
 *		   addGenericCharType(string $identifier)
 *		   Adds a new REBuilder_Pattern_GenericCharType class instance to this container
 *		   @see REBuilder_Pattern_GenericCharType::__construct
 * 
 * @method REBuilder_Pattern_Container addGenericCharTypeAndContinue()
 *		   addGenericCharTypeAndContinue(string $identifier)
 *		   Same as addGenericCharType but it returns the current container
 *		   @see REBuilder_Pattern_GenericCharType::__construct
 * 
 * @method REBuilder_Pattern_NonPrintingChar addNonPrintingChar()
 *		   addNonPrintingChar(string $identifier)
 *		   Adds a new REBuilder_Pattern_NonPrintingChar class instance to this container
 *		   @see REBuilder_Pattern_NonPrintingChar::__construct
 * 
 * @method REBuilder_Pattern_Container addNonPrintingCharAndContinue()
 *		   addNonPrintingCharAndContinue(string $identifier)
 *		   Same as addNonPrintingChar but it returns the current container
 *		   @see REBuilder_Pattern_NonPrintingChar::__construct
 * 
 * @method REBuilder_Pattern_SimpleAssertion addSimpleAssertion()
 *		   addSimpleAssertion(string $identifier)
 *		   Adds a new REBuilder_Pattern_SimpleAssertion class instance to this container
 *		   @see REBuilder_Pattern_SimpleAssertion::__construct
 * 
 * @method REBuilder_Pattern_Container addSimpleAssertionAndContinue()
 *		   addSimpleAssertionAndContinue(string $identifier)
 *		   Same as addSimpleAssertion but it returns the current container
 *		   @see REBuilder_Pattern_SimpleAssertion::__construct
 * 
 * @method REBuilder_Pattern_Dot addDot()
 *		   addDot()
 *		   Adds a new REBuilder_Pattern_Dot class instance to this container
 *		   @see REBuilder_Pattern_Dot::__construct
 * 
 * @method REBuilder_Pattern_Container addDotAndContinue()
 *		   addDotAndContinue()
 *		   Same as addDot but it returns the current container
 *		   @see REBuilder_Pattern_Dot::__construct
 * 
 * @method REBuilder_Pattern_Byte addByte()
 *		   addByte()
 *		   Adds a new REBuilder_Pattern_Byte class instance to this container
 *		   @see REBuilder_Pattern_Byte::__construct
 * 
 * @method REBuilder_Pattern_Container addByteAndContinue()
 *		   addByteAndContinue()
 *		   Same as addByte but it returns the current container
 *		   @see REBuilder_Pattern_Byte::__construct
 * 
 * @method REBuilder_Pattern_UnicodeCharClass addUnicodeCharClass()
 *		   addUnicodeCharClass(string $class, bool $negate)
 *		   Adds a new REBuilder_Pattern_UnicodeCharClass class instance to this container
 *		   @see REBuilder_Pattern_UnicodeCharClass::__construct
 * 
 * @method REBuilder_Pattern_Container addUnicodeCharClassAndContinue()
 *		   addUnicodeCharClassAndContinue(string $class, bool $negate)
 *		   Same as addUnicodeCharClass but it returns the current container
 *		   @see REBuilder_Pattern_UnicodeCharClass::__construct
 * 
 * @method REBuilder_Pattern_SubPattern addSubPattern()
 *		   addSubPattern(bool $capture, string $name, string $modifiers,
 *						 bool $groupMatches, bool $onceOnly)
 *		   Adds a new REBuilder_Pattern_SubPattern class instance to this container
 *		   @see REBuilder_Pattern_SubPattern::__construct
 * 
 * @method REBuilder_Pattern_Container addSubPatternAndContinue()
 *		   addSubPatternAndContinue(bool $capture, string $name, string $modifiers,
 *									bool $groupMatches, bool $onceOnly)
 *		   Same as addSubPattern but it returns the current container
 *		   @see REBuilder_Pattern_SubPattern::__construct
 */
abstract class REBuilder_Pattern_Container extends REBuilder_Pattern_Abstract
{
	/**
	 * Class children
	 *
	 * @var array
	 */
	protected $_children = array();
	
	/**
	 * Adds a child to the class
	 * 
	 * @param REBuilder_Pattern_Abstract $child Child to add
	 * @return REBuilder_Pattern_Container
	 */
	public function addChild (REBuilder_Pattern_Abstract $child)
	{
		$child->setParent($this);
		$this->_children[] = $child;
		return $this;
	}
	
	/**
	 * Removes a child from the class
	 * 
	 * @param REBuilder_Pattern_Abstract $child Child to remove
	 * @return REBuilder_Pattern_Container
	 */
	public function removeChild (REBuilder_Pattern_Abstract $child)
	{
		if ($this->hasChildren()) {
			$index = null;
			foreach ($this->getChildren() as $k => $c) {
				if (spl_object_hash($c) === spl_object_hash($child)) {
					$index = $k;
					break;
				}
			}
			if ($index !== null) {
				array_splice($this->_children, $index, 1);
			}
		}
		return $this;
	}
	
	/**
	 * Returns children array
	 * 
	 * @return array
	 */
	public function getChildren ()
	{
		return $this->_children;
	}
	
	/**
	 * Returns true if the class has at least one child
	 * 
	 * @return bool
	 */
	public function hasChildren ()
	{
		return count($this->getChildren()) !== 0;
	}
	
	/**
	 * Returns the string representation of class children
	 * 
	 * @return string
	 */
	public function renderChildren ()
	{
		$ret = "";
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $child) {
				$ret .= $child->render();
			}
		}
		return $ret;
	}
	
	/**
	 * Allow to call function in the form of addClass and addClassAndContinue
	 * 
	 * @param string $name	    Method name
	 * @param array  $arguments Method arguments
	 * @return mixed
	 */
	function __call ($name, $arguments)
	{
		//Add entity shortcut
		if (strpos($name, "add") === 0) {
			$name = str_replace("add", "", $name);
			$continue = strpos($name, "AndContinue") !== false;
			if ($continue) {
				$name = str_replace("AndContinue", "", $name);
			}
			$class = new ReflectionClass("REBuilder_Pattern_$name");
			if (!$class->isAbstract() &&
				$class->isSubclassOf("REBuilder_Pattern_Abstract")) {
				$instance = $class->newInstanceArgs($arguments);
				$this->addChild($instance);
				return $continue ? $this : $instance;
			}
		}
		trigger_error(
			"Call to undefined method " . __CLASS__ . "::$name()",
			E_USER_ERROR
		);
	}
}