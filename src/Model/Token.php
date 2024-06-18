<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Shopgate\WebCheckout\Api\TokenInterface;
use Magento\Framework\App\Request\Http;

class Token implements TokenInterface
{
    protected Http $request;

    public function __construct(
        Http $request
    )
    {
        $this->request = $request;
    }

    public function getCustomerToken(): string
    {
        // Your logic to generate and return a token goes here.
        return json_encode(['customer' => true]);
    }

    public function getGuestToken(): string
    {
        // Your logic to generate and return a token goes here.
        return json_encode(['guest' => true]);
    }
}
