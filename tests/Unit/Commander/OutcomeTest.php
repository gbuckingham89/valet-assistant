<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit\Commander;

use Gbuckingham89\ValetAssistant\Commander\Outcome;
use Gbuckingham89\ValetAssistant\Tests\TestCase;

class OutcomeTest extends TestCase
{
    public function test_get_output_lines()
    {
        $outcome = new Outcome([
            'Lorem ipsum dolor sit amet',
            'Consectetur adipiscing elit',
            'Maecenas convallis quis tellus et porta'
        ], 0);

        $this->assertEquals([
            'Lorem ipsum dolor sit amet',
            'Consectetur adipiscing elit',
            'Maecenas convallis quis tellus et porta'
        ], $outcome->getOutputLines());
    }

    public function test_get_result_code()
    {
        $outcome = new Outcome([
            'Lorem ipsum dolor sit amet',
            'Consectetur adipiscing elit',
            'Maecenas convallis quis tellus et porta'
        ], 0);

        $this->assertEquals(0, $outcome->getResultCode());
    }

    public function test_has_output()
    {
        $outcome1 = new Outcome([
            'Lorem ipsum dolor sit amet',
            'Consectetur adipiscing elit',
            'Maecenas convallis quis tellus et porta'
        ], 0);

        $this->assertTrue($outcome1->hasOutput());

        $outcome2 = new Outcome([''], 0);

        $this->assertFalse($outcome2->hasOutput());

        $outcome3 = new Outcome([], 0);

        $this->assertFalse($outcome3->hasOutput());
    }
}
