<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Shopgate\WebCheckout\Api\LogReaderInterface;
use Shopgate\WebCheckout\Api\LogResultInterface;
use SplFileObject;

class LogReader implements LogReaderInterface
{
    private SplFileObject $file;

    public function __construct(
        private readonly string $logFilePath,
        private readonly LogResultFactory $resultFactory,
        private readonly DirectoryList $directoryList,
        private readonly File $fileDriver,
    ) {
    }

    /**
     * Reads the lines of the log starting from the last one
     *
     * @throws FileSystemException
     */
    public function getPaginatedLogLines(int $page = 1, int $lines = 20): LogResultInterface
    {
        $basePath = $this->directoryList->getPath('base');
        if ($this->fileDriver->isExists($basePath. $this->logFilePath) === false) {
            throw new FileSystemException(__('Log file not found. Enable WebCheckout logging in the configuration.'));
        }
        $this->file = new SplFileObject($basePath . $this->logFilePath);
        $totalLines = $this->countLines();
        $totalPages = ceil($totalLines / $lines);
        $pageNumber = max(1, min($page, $totalPages));

        $startLine = max(0, $totalLines - ($pageNumber * $lines));
        $endLine = $startLine + $lines;

        $this->file->seek($startLine);
        $log = [];
        while (!$this->file->eof() && $this->file->key() < $endLine) {
            $log[$this->file->key()] = $this->file->current();
            $this->file->next();
        }
        $result = $this->resultFactory->create();

        return $result->setLog($log);
    }

    private function countLines(): int
    {
        $lineCount = 0;
        while (!$this->file->eof()) {
            $this->file->current();
            $lineCount++;
            $this->file->next();
        }
        $this->file->rewind();

        return $lineCount;
    }
}
