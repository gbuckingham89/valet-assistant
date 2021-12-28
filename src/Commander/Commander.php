<?php

namespace Gbuckingham89\ValetAssistant\Commander;

interface Commander
{
    /**
     * @param string $command
     *
     * @return \Gbuckingham89\ValetAssistant\Commander\Outcome
     */
    public function execute(string $command): Outcome;
}
