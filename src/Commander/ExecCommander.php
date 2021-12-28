<?php

namespace Gbuckingham89\ValetAssistant\Commander;

class ExecCommander implements Commander
{
    /**
     * @param string $command
     *
     * @return \Gbuckingham89\ValetAssistant\Commander\Outcome
     */
    public function execute(string $command): Outcome
    {
        $outputLines = [];
        $resultCode = null;

        exec($command, $outputLines, $resultCode);

        return new Outcome($outputLines, $resultCode);
    }
}
