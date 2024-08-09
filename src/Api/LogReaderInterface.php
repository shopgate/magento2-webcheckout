<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface LogReaderInterface
{
    /**
     * @param int $page
     * @param int $lines
     *
     * @return LogResultInterface
     */
    public function getPaginatedLogLines(int $page, int $lines): LogResultInterface;
}
