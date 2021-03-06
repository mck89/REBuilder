<?php
class AssertionTest extends PHPUnit_Framework_TestCase
{
    public function testLookaheadAssertion ()
    {
        $regex = REBuilder\REBuilder::parse("/a(?=b(?!c))/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);

        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[1]);
        $this->assertSame(true, $children[1]->getLookahead());
        $this->assertSame(false, $children[1]->getNegate());

        $children = $children[1]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("b", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[1]);
        $this->assertSame(true, $children[1]->getLookahead());
        $this->assertSame(true, $children[1]->getNegate());

        $children = $children[1]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("c", $children[0]->getChar());

        $this->assertSame("/a(?=b(?!c))/", $regex->render());
    }

    public function testLookbehindAssertion ()
    {
        $regex = REBuilder\REBuilder::parse("/(?<=(?<!a)b)c/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);

        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("c", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[0]);
        $this->assertSame(false, $children[0]->getLookahead());
        $this->assertSame(false, $children[0]->getNegate());

        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("b", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[0]);
        $this->assertSame(false, $children[0]->getLookahead());
        $this->assertSame(true, $children[0]->getNegate());

        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());

        $this->assertSame("/(?<=(?<!a)b)c/", $regex->render());
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addAssertion(false, true)
                    ->addCharAndContinue("a")
                ->getParent()
                ->addChar("b");
        $this->assertSame("/(?<!a)b/", $regex . "");
    }
}
