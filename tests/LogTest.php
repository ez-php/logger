<?php

declare(strict_types=1);

namespace Tests;

use EzPhp\Logging\Log;
use EzPhp\Logging\LoggerInterface;
use EzPhp\Logging\LogLevel;
use EzPhp\Logging\NullDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use RuntimeException;

/**
 * Class LogTest
 *
 * @package Tests
 */
#[CoversClass(Log::class)]
#[UsesClass(LogLevel::class)]
#[UsesClass(NullDriver::class)]
final class LogTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        Log::resetLogger();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Log::resetLogger();
    }

    /**
     * @return void
     */
    public function test_throws_when_logger_not_initialized(): void
    {
        $this->expectException(RuntimeException::class);
        Log::info('test');
    }

    /**
     * @return void
     */
    public function test_set_logger_wires_facade(): void
    {
        $spy = new class () implements LoggerInterface {
            /** @var list<array{level: LogLevel, message: string}> */
            public array $recorded = [];

            /** @param array<string, mixed> $context */
            public function log(LogLevel $level, string $message, array $context = []): void
            {
                $this->recorded[] = ['level' => $level, 'message' => $message];
            }

            /** @param array<string, mixed> $context */
            public function debug(string $message, array $context = []): void
            {
                $this->log(LogLevel::DEBUG, $message);
            }

            /** @param array<string, mixed> $context */
            public function info(string $message, array $context = []): void
            {
                $this->log(LogLevel::INFO, $message);
            }

            /** @param array<string, mixed> $context */
            public function warning(string $message, array $context = []): void
            {
                $this->log(LogLevel::WARNING, $message);
            }

            /** @param array<string, mixed> $context */
            public function error(string $message, array $context = []): void
            {
                $this->log(LogLevel::ERROR, $message);
            }

            /** @param array<string, mixed> $context */
            public function critical(string $message, array $context = []): void
            {
                $this->log(LogLevel::CRITICAL, $message);
            }
        };

        Log::setLogger($spy);

        Log::debug('a');
        Log::info('b');
        Log::warning('c');
        Log::error('d');
        Log::critical('e');
        Log::log(LogLevel::INFO, 'f');

        $this->assertCount(6, $spy->recorded);
        $this->assertSame(LogLevel::DEBUG, $spy->recorded[0]['level']);
        $this->assertSame('a', $spy->recorded[0]['message']);
        $this->assertSame(LogLevel::CRITICAL, $spy->recorded[4]['level']);
    }

    /**
     * @return void
     */
    public function test_reset_logger_clears_the_facade(): void
    {
        Log::setLogger(new NullDriver());
        Log::resetLogger();

        $this->expectException(RuntimeException::class);
        Log::info('should throw');
    }
}
