<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class OrderData implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly Context $httpContext,
        private readonly ShopgateDetector $shopgateDetector
    ) {
    }

    public function getSuccessPageData(): array
    {
        if (!$this->shopgateDetector->isShopgate()) {
            return [];
        }

        $isLoggedIn = $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
        $order = $this->checkoutSession->getLastRealOrder();

        $totals = [];
        $order->getGrandTotal() && array_push($totals, [
            'type' => 'grandTotal',
            'amount' => $order->getGrandTotal()
        ]);
        $order->getTaxAmount() && array_push($totals, [
            'type' => 'tax',
            'amount' => $order->getTaxAmount()
        ]);
        $order->getShippingAmount() && array_push($totals, [
            'type' => 'shipping',
            'amount' => $order->getShippingAmount()
        ]);

        $products = array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'quantity' => $item->getQtyOrdered(),
                'price' => [
                    'withTax' => $item->getPriceInclTax(),
                    'net' => $item->getBasePrice()
                ]
            ];
        }, $order->getAllItems());

        return [
            'order' => [
                'number' => $order->getId(),
                'currency' => $order->getOrderCurrencyCode(),
                'totals' => $totals,
                'products' => $products
            ],
            'guest' => !$isLoggedIn,
        ];
    }
}
