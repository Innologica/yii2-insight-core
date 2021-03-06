<?php


class DateUtilTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testWorkingDays()
    {
        //DST switch
        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-10-27', '2017-11-05 23:59:59');
        $this->tester->assertEquals(6, $days, 'Working days should be 6');

        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-11-16', '2017-11-17 23:59:59');
        $this->tester->assertEquals(2, $days, 'Working days should be 2');

        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-11-15', '2017-11-15 23:59:59');
        $this->tester->assertEquals(1, $days, 'Working days should be 1');

        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-11-18', '2017-11-19 23:59:59');
        $this->tester->assertEquals(0, $days, 'Working days should be 0');

        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-11-17', '2017-11-20 23:59:59');
        $this->tester->assertEquals(2, $days, 'Working days should be 2');
    }

    public function testVacationsDays()
    {
        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-12-21', '2018-01-02 23:59:59', [
            '2017-12-24', '2017-12-25', '2017-12-26', '2017-12-27',
        ]);
        $this->tester->assertEquals(6, $days, 'Working days should be 6');

        $days = \insight\core\util\DateTimeUtil::getWorkingDays('2017-12-28', '2018-01-03 23:59:59', [
            '2018-01-01',
        ]);
        $this->tester->assertEquals(4, $days, 'Working days should be 4');
    }

}