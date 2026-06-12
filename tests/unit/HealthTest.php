<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Config\App;
use Tests\Support\Libraries\ConfigReader;

final class HealthTest extends TestCase
{
    public function testIsDefinedAppPath(): void
    {
        $this->assertTrue(defined('APPPATH'));
    }

    public function testBaseUrlHasBeenSet(): void
    {
        $app = new App();

        $this->assertIsString($app->baseURL);
        $this->assertNotEmpty($app->baseURL);
        $this->assertStringContainsString('http', $app->baseURL);
    }
}