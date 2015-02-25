<?php
class RegexTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a/i");
		
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$this->assertSame("/", $regex->getStartDelimiter());
		$this->assertSame("/", $regex->getEndDelimiter());
		$this->assertSame("i", $regex->getModifiers());
		$this->assertSame("/a/i", $regex->render());
	}
	
	public function testBracketStyledelimiters ()
	{
		$regex = REBuilder::parse("(a)i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$this->assertSame("(", $regex->getStartDelimiter());
		$this->assertSame(")", $regex->getEndDelimiter());
		$this->assertSame("i", $regex->getModifiers());
		$this->assertSame("(a)i", $regex->render());
	}
	
	public function invalidDelimitersProvider () {
		return array(
			array("\\"),
			array("1"),
			array("a"),
			array(" ")
		);
	}
	
	/**
	 * @dataProvider invalidDelimitersProvider
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testInvalidDelimiterException ($delimiter)
    {
		new REBuilder_Pattern_Regex("", $delimiter);
    }
	
	/**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testMissingDelimiterException ()
    {
		REBuilder::parse("#a");
    }
	
	/**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testEscapedDelimiterException ()
    {
		REBuilder::parse("#a\#");
    }
    
    /**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testUnescapedDelimiterInsideRegexException ()
    {
		REBuilder::parse("#a#b#");
    }
	
	/**
     * @expectedException REBuilder_Exception_InvalidModifier
     */
    public function testInvalidModifierException ()
    {
		REBuilder::create("$");
    }
	
	/**
     * @expectedException REBuilder_Exception_EmptyRegex
     */
    public function testEmptyRegexException ()
    {
		REBuilder::parse("");
    }
	
	/**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportRepetitionException ()
    {
		REBuilder::create()->setRepetition("*");
    }
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create("i", "#");
		$this->assertSame("##i", $regex->render());
	}
}
