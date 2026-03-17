<?php

declare(strict_types=1);

namespace Tests;

use EzPhp\Testing\ApplicationTestCase as EzPhpApplicationTestCase;

/**
 * Base class for logging module tests that need a bootstrapped Application.
 *
 * The default getBasePath() from EzPhp\Testing\ApplicationTestCase creates a
 * temporary directory with an empty config/ subdirectory. This satisfies
 * ConfigLoader without requiring a real application structure. Config values
 * are resolved lazily — missing config keys return their defaults, so
 * LogServiceProvider falls back to FileDriver with {basePath}/storage/logs.
 *
 * Override configureApplication() to register providers or bind services
 * before bootstrap.
 *
 * @package Tests
 */
abstract class ApplicationTestCase extends EzPhpApplicationTestCase
{
}
