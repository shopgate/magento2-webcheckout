<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

/**
 * @api
 */
interface LogResultInterface
{
    /**
     * @return string[]
     */
    public function getLog(): array;

    /**
     * @param string[] $log
     *
     * @return LogResultInterface
     */
    public function setLog(array $log): LogResultInterface;
}
