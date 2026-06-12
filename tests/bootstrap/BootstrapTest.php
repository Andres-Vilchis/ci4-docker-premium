<?php

declare(strict_types=1);

namespace Tests\Bootstrap;

use PHPUnit\Framework\TestCase;

final class BootstrapTest extends TestCase
{
    public function test_environment_is_defined(): void
    {
        $this->assertTrue(
            defined('ENVIRONMENT'),
            'ENVIRONMENT should be defined by CI4 bootstrap'
        );

        $this->assertIsString(ENVIRONMENT);
    }

    public function test_paths_are_defined(): void
    {
        $this->assertTrue(defined('APPPATH'));
        $this->assertTrue(defined('SYSTEMPATH'));
        $this->assertTrue(defined('WRITEPATH'));
    }

    public function test_fcp_path_exists_in_web_context(): void
    {
        $this->assertTrue(defined('FCPATH'));
        $this->assertDirectoryExists(ROOTPATH . 'app');
    }
}