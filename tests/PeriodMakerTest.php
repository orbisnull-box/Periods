<?php
require_once "PHPUnit/Autoload.php";
require_once "../PeriodMaker.php";
require_once "../in.php";

class PeriodMakerTest extends PHPUnit_Framework_TestCase {
    /**
     * @var PeriodMaker
     */
    protected $_maker;
    protected $_inData;
    protected $_inPeriods;

   public  function setUp()
   {
       $this->_maker = new PeriodMaker();
       $this->_inData = getInArray();
       $this->_inPeriods = getInToPeriods();
       $this->_maker->load($this->_inData);
   }

    public function tearDown()
    {
        unset ($this->_maker);
        unset ($this->_inData);
        unset($this->_inPeriods);
    }


    public function testTrue() {
        $this->assertTrue(true);
    }

    public function testLoadData()
    {
        $maker = new PeriodMaker();
        $maker->load($this->_inData);
        $this->assertEquals($this->_inData, $maker->getInData());
    }

    public function testGetFirstBegin()
    {
        $this->assertEquals("2010-12-12", $this->_maker->getFirstBegin());
    }

    public function testGetLastEnd()
    {
        $this->assertEquals("2011-03-20", $this->_maker->getLastEnd());
    }

    public function testGetPoints()
    {
        $points = $this->_maker->getPoints(1);
        $this->assertCount(8, $points);
        $this->assertEquals(array("id"=>1, "date"=>"2010-12-20"), $points[0]);

        $points = $this->_maker->getPoints(2);
        $this->assertCount(8, $points);
        $this->assertEquals(array("id"=>8, "date"=>"2010-12-12"), $points[0]);
    }

    public function testGetTypes()
    {
        $types = array(1,2);
        $this->assertEquals($types, $this->_maker->getTypes());
    }

    public function testGetPeriodInType()
    {
        $data = $this->_maker->getInData();
        $this->assertEquals($data[0], $this->_maker->getPeriodRowInType($data[0]["begin"], $data[0]["type"]));
        $this->assertEquals($data[2], $this->_maker->getPeriodRowInType($data[2]["begin"], $data[2]["type"]));
        $this->assertEquals($data[4], $this->_maker->getPeriodRowInType($data[5]["begin"], $data[5]["type"]));
        $this->assertEquals($data[4], $this->_maker->getPeriodRowInType($data[6]["begin"], $data[6]["type"]));
        $this->assertEquals($data[5], $this->_maker->getPeriodRowInType("2011-01-20", 1));
    }

    public function testGetCurrentEnd()
    {
        $this->assertEquals("2011-01-05", $this->_maker->getCurrentFullEnd("2010-12-12"));
        $this->assertEquals("2011-01-09", $this->_maker->getCurrentFullEnd("2011-01-02"));
        $this->assertEquals("2011-01-20", $this->_maker->getCurrentFullEnd("2011-01-09"));
        $this->assertEquals("2011-02-05", $this->_maker->getCurrentFullEnd("2011-02-01"));
    }

    public function testGetBeginInPeriodType()
    {
        $begin = $this->_maker->getFirstBegin();
        $end = $this->_maker->getCurrentFullEnd($begin);
        $this->assertEquals($this->_inData[4], $this->_maker->getBeginInPeriodType($begin, $end, 1));
        $this->assertEquals($this->_inData[1], $this->_maker->getBeginInPeriodType($begin, $end, 2));
    }

    public function testIsBeginTopRowType()
    {
        $this->assertFalse($this->_maker->isBeginTopRowType($this->_inData[5]), "fist");
        $this->assertTrue($this->_maker->isBeginTopRowType($this->_inData[3]), "second");
        $this->assertFalse($this->_maker->isBeginTopRowType($this->_inData[7]), "sri");
    }

    public function testGetBeginInPeriod()
    {
        $begin = $this->_maker->getFirstBegin();
        $end = $this->_maker->getCurrentFullEnd($begin);
        $this->assertEquals("2010-12-20", $this->_maker->getBeginInPeriod($begin, $end), "fist");

        $begin = "2010-12-20";
        $end = $this->_maker->getCurrentFullEnd($begin);
        $this->assertEquals("2011-01-02", $this->_maker->getBeginInPeriod($begin, $end), "second");

        $begin = "2011-01-02";
        $end = $this->_maker->getCurrentFullEnd($begin);
        $this->assertEquals("2011-01-09", $this->_maker->getBeginInPeriod($begin, $end), "second");

        $begin = "2011-01-09";
        $end = $this->_maker->getCurrentFullEnd($begin);
        $this->assertEquals("2011-01-20", $this->_maker->getBeginInPeriod($begin, $end), "second");
    }


    public function testGetPeriods()
    {
        $this->assertEquals($this->_inPeriods, $this->_maker->getPeriods());
    }
}