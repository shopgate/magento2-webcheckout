<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Controller\Close;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(private readonly PageFactory $resultPageFactory)
    {
    }

    public function execute(): Page
    {
        return $this->resultPageFactory->create();
    }
}
