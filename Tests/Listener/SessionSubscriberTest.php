<?php

namespace Theodo\Evolution\Bundle\SessionBundle\Listener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Theodo\Evolution\Bundle\SessionBundle\Manager\BagManagerInterface;

class SessionSubscriberTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Request
     */
    private $request;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|BagManagerInterface
     */
    private $bagManager;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|SessionSubscriber
     */
    private  $listener;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|RequestEvent
     */
    private $eventMock;

    public function setUp(): void
    {
        $this->request = $this->getMockBuilder(Request::class)->getMock();
        $this->bagManager = $this->getMockBuilder(BagManagerInterface::class)->getMock();
        $this->listener = new SessionSubscriber($this->bagManager);

        $this->request->expects($this->any())
            ->method('getSession')
            ->will(
                $this->returnValue(
                    $this->getMockBuilder(SessionInterface::class)->getMock()
                )
            );

        $this->eventMock = $this->getMockBuilder(RequestEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
    }

    /**
     * @dataProvider getRequestExpectations
     */
    public function testIsInitializedOnMasterRequestAndHasSession($requestType, $hasSession, $shouldInitialize)
    {
        $this->eventMock->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue($requestType));

        $this->request->expects($this->any())
            ->method('hasSession')
            ->will($this->returnValue($hasSession));

        $this->bagManager->expects($shouldInitialize ? $this->once() : $this->never())
            ->method('initialize');

        $this->listener->onKernelRequest($this->eventMock);
    }

    public function getRequestExpectations()
    {
        return array(
            array(HttpKernelInterface::MASTER_REQUEST, true, true),
            array(HttpKernelInterface::MASTER_REQUEST, false, false),
            array(HttpKernelInterface::SUB_REQUEST, false, false),
            array(HttpKernelInterface::SUB_REQUEST, true, false),
        );
    }
}
