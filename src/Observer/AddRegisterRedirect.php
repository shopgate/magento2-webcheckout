<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Controller\Account\CreatePost;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;

class AddRegisterRedirect implements ObserverInterface
{
    use ShopgateDetect;

    public function __construct(
        private readonly EncoderInterface $encoder,
        private readonly CheckoutSession $checkoutSession,
        private readonly UrlInterface $urlInterface,
        private readonly RequestInterface $request,
    ) {
    }

    /**
     * When a customer registers, we direct them to a
     * special page that logs them in the App & closes
     * inApp browser
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isShopgate($this->request, $this->checkoutSession)) {
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
