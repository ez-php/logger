<?php

declare(strict_types=1);

namespace Tests;

use EzPhp\Logging\LogLevel;
use EzPhp\Logging\NullDriver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * Class NullDriverTest
 *
 * @package Tests
 */
#[CoversClass(NullDriver::class)]
#[UsesClass(LogLevel::class)]
final class NullDriverTest extends TestCase
{
    /**
     * @return void
     */
    public function test_log_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->log(LogLevel::ERROR, 'msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }

    /**
     * @return void
     */
    public function test_debug_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->debug('msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }

    /**
     * @return void
     */
    public function test_info_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->info('msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }

    /**
     * @return void
     */
    public function test_warning_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->warning('msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }

    /**
     * @return void
     */
    public function test_error_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->error('msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }

    /**
     * @return void
     */
    public function test_critical_produces_no_output(): void
    {
        ob_start();
        (new NullDriver())->critical('msg');
        $output = (string) ob_get_clean();

        $this->assertSame('', $output);
    }
}
