<?php

declare(strict_types=1);

namespace EzPhp\Logging;

/**
 * Class FileDriver
 *
 * Appends log entries to a daily-rotated file inside the given directory.
 * File naming: app-YYYY-MM-DD.log
 * Line format:  [YYYY-MM-DD HH:MM:SS] LEVEL: message {json_context}
 *
 * When $maxBytes is greater than zero the current log file is gzip-compressed
 * and archived before the next write whenever its size exceeds the threshold.
 * Archive naming: app-YYYY-MM-DD-{unix_timestamp}.log.gz
 *
 * @package EzPhp\Logging
 */
final class FileDriver implements LoggerInterface
{
    /**
     * FileDriver Constructor
     *
     * @param string $directory Absolute path to the log directory (created on demand).
     * @param int    $maxBytes  Maximum file size in bytes before rotation (0 = disabled).
     */
    public function __construct(
        private readonly string $directory,
        private readonly int $maxBytes = 0,
    ) {
    }

    /**
     * @param LogLevel             $level
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function log(LogLevel $level, string $message, array $context = []): void
    {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0o755, true);
        }

        if ($this->maxBytes > 0) {
            $this->rotateIfNeeded();
        }

        file_put_contents(
            $this->filePath(),
            $this->formatLine($level, $message, $context),
            FILE_APPEND | LOCK_EX,
        );
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @return string
     */
    private function filePath(): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . 'app-' . date('Y-m-d') . '.log';
    }

    /**
     * @param LogLevel             $level
     * @param string               $message
     * @param array<string, mixed> $context
     *
     * @return string
     */
    private function formatLine(LogLevel $level, string $message, array $context): string
    {
        $datetime = date('Y-m-d H:i:s');
        $upper = strtoupper($level->value);
        $ctx = $context !== [] ? ' ' . (json_encode($context) ?: '{}') : '';

        return "[$datetime] $upper: $message$ctx\n";
    }

    /**
     * Rotate the current log file when its size exceeds $maxBytes.
     *
     * Compresses the file to a .gz archive and removes the original.
     * Falls back to a plain rename with a .bak suffix if gzip fails.
     *
     * @return void
     */
    private function rotateIfNeeded(): void
    {
        $path = $this->filePath();

        if (!is_file($path)) {
            return;
        }

        $size = filesize($path);

        if ($size === false || $size < $this->maxBytes) {
            return;
        }

        $archive = $this->directory . DIRECTORY_SEPARATOR . 'app-' . date('Y-m-d') . '-' . time() . '.log.gz';

        $gz = gzopen($archive, 'wb9');

        if (is_resource($gz)) {
            $content = file_get_contents($path);

            if (is_string($content)) {
                gzwrite($gz, $content);
            }

            gzclose($gz);
            unlink($path);
        } else {
            rename($path, $archive . '.bak');
        }
    }
}
