<?php

declare(strict_types=1);

namespace EzPhp\Logging;

/**
 * Enum LogLevel
 *
 * Supported log severity levels, ordered from least to most severe.
 *
 * @package EzPhp\Logging
 */
enum LogLevel: string
{
    case DEBUG = 'debug';

    case INFO = 'info';

    case WARNING = 'warning';

    case ERROR = 'error';

    case CRITICAL = 'critical';

    /**
     * Returns all levels in ascending severity order.
     *
     * @return list<self>
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Returns the numeric severity of this level (DEBUG=0 … CRITICAL=4).
     *
     * @return int
     */
    public function severity(): int
    {
        return match ($this) {
            self::DEBUG => 0,
            self::INFO => 1,
            self::WARNING => 2,
            self::ERROR => 3,
            self::CRITICAL => 4,
        };
    }

    /**
     * Returns true when this level is at least as severe as the given minimum.
     *
     * @param LogLevel $min The minimum required severity level.
     *
     * @return bool
     */
    public function isAtLeast(LogLevel $min): bool
    {
        return $this->severity() >= $min->severity();
    }

    /**
     * Resolve a level from its string value. Wraps the native enum from() for
     * explicit, traceable usage in service providers and configuration readers.
     *
     * @param string $value The string value of the level (e.g. 'info', 'error').
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return self::from($value);
    }
}
