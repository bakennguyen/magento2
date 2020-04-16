<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Test\Unit\Controller;

use \Magento\Setup\Controller\CompleteBackup;

class CompleteBackupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Controller
     *
     * @var \Magento\Setup\Controller\CompleteBackup
     */
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new CompleteBackup();
    }

    public function testIndexAction()
    {
        $viewModel = $this->controller->indexAction();
        $this->assertInstanceOf(\Laminas\View\Model\ViewModel::class, $viewModel);
        $this->assertSame('/error/404.phtml', $viewModel->getTemplate());
        $this->assertSame(
            \Laminas\Http\Response::STATUS_CODE_404,
            $this->controller->getResponse()->getStatusCode()
        );
    }

    public function testProgressAction()
    {
        $viewModel = $this->controller->progressAction();
        $this->assertInstanceOf(\Laminas\View\Model\ViewModel::class, $viewModel);
        $this->assertTrue($viewModel->terminate());
        $this->assertSame('/magento/setup/complete-backup/progress.phtml', $viewModel->getTemplate());
    }
}
