<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Controller;

use \Magento\Setup\Controller\Session;

class SessionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject| \Magento\Setup\Model\ObjectManagerProvider
     */
    private $objectManagerProvider;

    /**
     * @var \Laminas\ServiceManager\ServiceManager
     */
    private $serviceManager;

    protected function setUp(): void
    {
        $objectManager =
            $this->getMockForAbstractClass(\Magento\Framework\ObjectManagerInterface::class, [], '', false);
        $objectManagerProvider =
            $this->createPartialMock(\Magento\Setup\Model\ObjectManagerProvider::class, ['get']);
        $this->objectManager = $objectManager;
        $this->objectManagerProvider = $objectManagerProvider;
        $this->serviceManager = $this->createPartialMock(\Laminas\ServiceManager\ServiceManager::class, ['get']);
    }

    /**
     * @covers \Magento\Setup\Controller\Session::unloginAction
     */
    public function testUnloginAction()
    {
        $this->objectManagerProvider->expects($this->once())->method('get')->willReturn(
            $this->objectManager
        );
        $deployConfigMock =
            $this->createPartialMock(\Magento\Framework\App\DeploymentConfig::class, ['isAvailable']);
        $deployConfigMock->expects($this->once())->method('isAvailable')->willReturn(true);

        $sessionMock = $this->createPartialMock(
            \Magento\Backend\Model\Auth\Session::class,
            ['prolong', 'isSessionExists']
        );
        $sessionMock->expects($this->once())->method('isSessionExists')->willReturn(false);

        $stateMock = $this->createPartialMock(\Magento\Framework\App\State::class, ['setAreaCode']);
        $stateMock->expects($this->once())->method('setAreaCode');

        $sessionConfigMock =
            $this->createPartialMock(\Magento\Backend\Model\Session\AdminConfig::class, ['setCookiePath']);
        $sessionConfigMock->expects($this->once())->method('setCookiePath');
        $urlMock = $this->createMock(\Magento\Backend\Model\Url::class);

        $returnValueMap = [
            [\Magento\Backend\Model\Auth\Session::class, $sessionMock],
            [\Magento\Framework\App\State::class, $stateMock],
            [\Magento\Backend\Model\Session\AdminConfig::class, $sessionConfigMock],
            [\Magento\Backend\Model\Url::class, $urlMock]
        ];

        $this->serviceManager->expects($this->once())->method('get')->willReturn($deployConfigMock);

        $this->objectManager->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnMap($returnValueMap);

        $this->objectManager->expects($this->once())
            ->method('create')
            ->willReturn($sessionMock);
        $controller = new Session($this->serviceManager, $this->objectManagerProvider);
        $urlMock->expects($this->once())->method('getBaseUrl');
        $controller->prolongAction();
    }

    /**
     * @covers \Magento\Setup\Controller\SystemConfig::indexAction
     */
    public function testIndexAction()
    {
        /** @var $controller Session */
        $controller = new Session($this->serviceManager, $this->objectManagerProvider);
        $viewModel = $controller->unloginAction();
        $this->assertInstanceOf(\Laminas\View\Model\ViewModel::class, $viewModel);
    }

    /**
     * @covers \Magento\Setup\Controller\SystemConfig::prolongAction
     */
    public function testProlongActionWithExistingSession()
    {
        $this->objectManagerProvider->expects($this->once())->method('get')->willReturn(
            $this->objectManager
        );
        $deployConfigMock =
            $this->createPartialMock(\Magento\Framework\App\DeploymentConfig::class, ['isAvailable']);
        $deployConfigMock->expects($this->once())->method('isAvailable')->willReturn(true);
        $sessionMock = $this->createPartialMock(
            \Magento\Backend\Model\Auth\Session::class,
            ['prolong', 'isSessionExists']
        );
        $sessionMock->expects($this->once())->method('isSessionExists')->willReturn(true);

        $this->serviceManager->expects($this->once())->method('get')->willReturn($deployConfigMock);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->willReturn($sessionMock);
        $controller = new Session($this->serviceManager, $this->objectManagerProvider);
        $this->assertEquals(new \Laminas\View\Model\JsonModel(['success' => true]), $controller->prolongAction());
    }
}
