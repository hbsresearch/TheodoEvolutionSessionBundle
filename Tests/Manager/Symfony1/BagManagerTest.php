<?php

namespace Theodo\Evolution\Bundle\SessionBundle\Tests\Manager\Symfony1;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Theodo\Evolution\Bundle\SessionBundle\Attribute\ScalarBag;
use Theodo\Evolution\Bundle\SessionBundle\Manager\Symfony1\BagConfiguration;
use Theodo\Evolution\Bundle\SessionBundle\Manager\Symfony1\BagManager;

/**
 * Symfony1\BagManagerTest class.
 *
 * @author Benjamin Grandfond <benjamin.grandfond@gmail.com>
 */
class BagManagerTest extends TestCase
{
    public function testInitialize()
    {
        $session = new Session(new MockArraySessionStorage());

        $configuration = new BagConfiguration();
        $manager = new BagManager($configuration);
        $manager->initialize($session);

        foreach ($configuration->getNamespaces() as $namespace) {
            if ($configuration->isArray($namespace)) {
                $this->assertInstanceOf(AttributeBag::class, $session->getBag($namespace));
            } else {
                $this->assertInstanceOf(ScalarBag::class, $session->getBag($namespace));
            }
        }
    }
}
