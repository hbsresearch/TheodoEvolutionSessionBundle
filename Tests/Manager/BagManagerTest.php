<?php
namespace Theodo\Evolution\Bundle\SessionBundle\Manager;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Theodo\Evolution\Bundle\SessionBundle\Attribute\ScalarBag;
use Theodo\Evolution\Bundle\SessionBundle\Manager\BagManager;

class BagManagerTest extends TestCase
{
    public function testInitialize()
    {
        $configuration = $this->getMockBuilder(BagManagerConfigurationInterface::class)->getMock();
        $configuration->expects($this->once())
            ->method('getNamespaces')
            ->will($this->returnValue(array('array', 'scalar')))
        ;

        $configuration->expects($this->exactly(2))
            ->method('isArray')
            ->with($this->anything())
            ->will($this->onConsecutiveCalls(true, false))
        ;

        $session = new Session(new MockArraySessionStorage());

        $manager = new BagManager($configuration);
        $manager->initialize($session);

        $this->assertInstanceOf(AttributeBag::class, $session->getBag('array'));
        $this->assertInstanceOf(ScalarBag::class, $session->getBag('scalar'));
    }
}
