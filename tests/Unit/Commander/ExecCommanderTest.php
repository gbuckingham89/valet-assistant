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

    public function test_path_is_used_for_execution_when_given()
    {
        // This causes an error message in the test output: ..sh: which: command not found
        // @todo Would be good to find a way to perform this test without the error.
        $outcome = (new ExecCommander('/some/fake/directory:/another-fake'))->execute('which nano');

        $this->assertInstanceOf(Outcome::class, $outcome);

        $this->assertEquals(127, $outcome->getResultCode());

        $this->assertIsArray($outcome->getOutputLines());
        $this->assertCount(0, $outcome->getOutputLines());
    }

    public function test_env_path_isnt_leaked_to_external_exec_calls()
    {
        (new ExecCommander('/some/fake/directory:/another-fake'))->execute('');

        $externalExecPath = exec('echo $PATH');

        $this->assertNotEquals('/some/fake/directory:/another-fake', $externalExecPath);
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
