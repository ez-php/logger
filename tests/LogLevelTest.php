<?php

declare(strict_types=1);

namespace Tests;

use EzPhp\Logging\LogLevel;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Class LogLevelTest
 *
 * @package Tests
 */
#[CoversClass(LogLevel::class)]
final class LogLevelTest extends TestCase
{
    /**
     * @return void
     */
    public function test_cases_have_expected_values(): void
    {
        $this->assertSame('debug', LogLevel::DEBUG->value);
        $this->assertSame('info', LogLevel::INFO->value);
        $this->assertSame('warning', LogLevel::WARNING->value);
        $this->assertSame('error', LogLevel::ERROR->value);
        $this->assertSame('critical', LogLevel::CRITICAL->value);
    }

    /**
     * @return void
     */
    public function test_all_returns_all_levels_in_order(): void
    {
        $levels = LogLevel::all();

        $this->assertSame([
            LogLevel::DEBUG,
            LogLevel::INFO,
            LogLevel::WARNING,
            LogLevel::ERROR,
            LogLevel::CRITICAL,
        ], $levels);
    }
}
