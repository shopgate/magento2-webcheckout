<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Customer\Controller\Account\CreatePost;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class AddRegisterRedirect implements ObserverInterface
{
    public function __construct(
        private readonly EncoderInterface $encoder,
        private readonly UrlInterface $urlInterface,
        private readonly ShopgateDetector $shopgateDetector
    ) {
    }

    /**
     * When a customer registers, we direct them to a
     * special page that logs them in the App & closes
     * inApp browser
     */
    public function execute(Observer $observer): void
    {
        if (!$this->shopgateDetector->isShopgate()) {
            return;
        }

        /** @var CreatePost $controller */
        $controller = $observer->getData('account_controller');
        $redirect = $this->urlInterface->getUrl('sgwebcheckout/account/registered');
        $referrer = $this->encoder->encode($redirect);
        $controller->getRequest()->setParams(
            array_merge($controller->getRequest()->getParams(), [CustomerUrl::REFERER_QUERY_PARAM_NAME => $referrer])
        );
    }
}
