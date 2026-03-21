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
    public function test_cases_have_expected_string_values(): void
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
    public function test_all_returns_all_cases_in_order(): void
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

    /**
     * @return void
     */
    public function test_from_string_resolves_case(): void
    {
        $this->assertSame(LogLevel::INFO, LogLevel::from('info'));
        $this->assertSame(LogLevel::ERROR, LogLevel::from('error'));
    }

    /**
     * @return void
     */
    public function test_severity_returns_expected_integers(): void
    {
        $this->assertSame(0, LogLevel::DEBUG->severity());
        $this->assertSame(1, LogLevel::INFO->severity());
        $this->assertSame(2, LogLevel::WARNING->severity());
        $this->assertSame(3, LogLevel::ERROR->severity());
        $this->assertSame(4, LogLevel::CRITICAL->severity());
    }

    /**
     * @return void
     */
    public function test_is_at_least_same_level(): void
    {
        $this->assertTrue(LogLevel::WARNING->isAtLeast(LogLevel::WARNING));
    }

    /**
     * @return void
     */
    public function test_is_at_least_higher_level(): void
    {
        $this->assertTrue(LogLevel::ERROR->isAtLeast(LogLevel::WARNING));
        $this->assertTrue(LogLevel::CRITICAL->isAtLeast(LogLevel::DEBUG));
    }

    /**
     * @return void
     */
    public function test_is_at_least_lower_level_returns_false(): void
    {
        $this->assertFalse(LogLevel::DEBUG->isAtLeast(LogLevel::INFO));
        $this->assertFalse(LogLevel::INFO->isAtLeast(LogLevel::WARNING));
    }

    /**
     * @return void
     */
    public function test_from_string_resolves_all_levels(): void
    {
        $this->assertSame(LogLevel::DEBUG, LogLevel::fromString('debug'));
        $this->assertSame(LogLevel::INFO, LogLevel::fromString('info'));
        $this->assertSame(LogLevel::WARNING, LogLevel::fromString('warning'));
        $this->assertSame(LogLevel::ERROR, LogLevel::fromString('error'));
        $this->assertSame(LogLevel::CRITICAL, LogLevel::fromString('critical'));
    }
}
