<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Shopgate\WebCheckout\Api\LogResultInterface;

class LogResult implements LogResultInterface
{
    private array $log;

    public function getLog(): array
    {
        return $this->log;
    }

    public function setLog(array $log): LogResultInterface
    {
        $this->log = $log;

        return $this;
    }
}
