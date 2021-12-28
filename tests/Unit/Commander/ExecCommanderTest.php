<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit\Commander;

use Gbuckingham89\ValetAssistant\Commander\ExecCommander;
use Gbuckingham89\ValetAssistant\Commander\Outcome;
use Gbuckingham89\ValetAssistant\Tests\TestCase;

class ExecCommanderTest extends TestCase
{
    public function test_single_line_output()
    {
        $outcome = (new ExecCommander())->execute('which nano');

        $this->assertInstanceOf(Outcome::class, $outcome);

        $this->assertEquals(0, $outcome->getResultCode());

        $this->assertIsArray($outcome->getOutputLines());
        $this->assertCount(1, $outcome->getOutputLines());
        $this->assertStringEndsWith('/nano', $outcome->getOutputLines()[0]);
    }

    public function test_multi_line_output()
    {
        $outcome = (new ExecCommander())->execute('cd / && ls -la');

        $this->assertInstanceOf(Outcome::class, $outcome);

        $this->assertEquals(0, $outcome->getResultCode());

        $this->assertIsArray($outcome->getOutputLines());
        $this->assertGreaterThan(1, count($outcome->getOutputLines()));
    }

    public function test_with_error_and_no_output()
    {
        $outcome = (new ExecCommander())->execute('which some-fake-non-existing-binary');

        $this->assertInstanceOf(Outcome::class, $outcome);

        $this->assertEquals(1, $outcome->getResultCode());

        $this->assertIsArray($outcome->getOutputLines());
        $this->assertCount(0, $outcome->getOutputLines());
    }
}
