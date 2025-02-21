<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use DateTimeZone;
use Magento\Framework\Exception\NoSuchEntityException;
use Shopgate\WebCheckout\Model\Config;

/**
 * Rewritten logger to write only when logging config is enabled.
 * Also, it writes to only our log file
 */
class Logger extends \Monolog\Logger
{
    /**
     * @throws NoSuchEntityException
     */
    public function __construct(
        string $name,
        Config $config,
        array $handlers = [],
        array $processors = [],
        ?DateTimeZone $timezone = null
    ) {
        $newHandlers = [];
        if ($config->isLoggingEnabled() && isset($handlers['webc_log'])) {
            $newHandlers[] = $handlers['webc_log'];
        }
        parent::__construct($name, $newHandlers, $processors, $timezone);
    }
}
