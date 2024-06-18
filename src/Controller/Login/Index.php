<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Controller\Login;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    private PageFactory $pageFactory;
    private RequestInterface $request;

    public function __construct(PageFactory $pageFactory, RequestInterface $request)
    {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }

    public function execute(): Page|ResultInterface|ResponseInterface
    {
        // Get the params that were passed from our Router
        $firstParam = $this->request->getParam('first_param');

        return $this->pageFactory->create();
    }
}
