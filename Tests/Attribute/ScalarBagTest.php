<?php

namespace Theodo\Evolution\Bundle\SessionBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Theodo\Evolution\Bundle\SessionBundle\Attribute\ScalarBag;

/**
 * ScalarBag class.
 *
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
class ScalarBagTest extends TestCase
{
    /**
     * @dataProvider getSessions
     * @param $session
     * @param $expected
     */
    public function testInitialize($session, $expected)
    {
        $bag = new ScalarBag('');
        $bag->initialize($session);

        $this->assertEquals($expected, $bag->get());
    }

    public function testSessionSetting()
    {
        $bag = new ScalarBag('bag');
        $sessionEntry = array();

        $bag->initialize($sessionEntry);
        $bag->set(true);

        $this->assertTrue($sessionEntry);
    }

    public function getSessions()
    {
        return array(
            array(array('foo'), 'foo'),
            array(array('foo', 'bar'), 'foo'),
            array(array(), null),
        );
    }
}
