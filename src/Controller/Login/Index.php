<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Controller\Login;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly RequestInterface $request,
        private readonly ResultFactory $resultFactory,
        private readonly Session $customerSession
    ) {
    }

    public function execute(): Page|ResultInterface|ResponseInterface
    {
        $closeInAppRoute = ''; // todo: create a route that closes inApp
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $token = $this->request->getParam('token');
        // todo: decrypt token logic

        $customerId = ''; // todo: retrieve from decrypted token
        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException|LocalizedException) {
            $resultRedirect->setPath($closeInAppRoute);
            return $resultRedirect;
        }
        $this->customerSession->setCustomerId($customerId);
        // this will prompt a cart load via `customer_login` observer
        $this->customerSession->setCustomerDataAsLoggedIn($customer);
        // Get the params that were passed from our Router

        return $resultRedirect->setUrl('');
    }
}
