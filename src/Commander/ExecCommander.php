<?php

namespace Gbuckingham89\ValetAssistant\Commander;

class ExecCommander implements Commander
{
    /**
     * @var string|null
     */
    protected ?string $envPath;

    /**
     * @param string|null $envPath
     */
    public function __construct(?string $envPath = null)
    {
        $this->envPath = $envPath;
    }

    /**
     * @param string $command
     *
     * @return \Gbuckingham89\ValetAssistant\Commander\Outcome
     */
    public function execute(string $command): Outcome
    {
        $outputLines = [];
        $resultCode = null;

        if (!empty($this->envPath)) {
            $command = 'PATH=' . $this->envPath . ' ' . $command;
        }

        exec($command, $outputLines, $resultCode);

        return new Outcome($outputLines, $resultCode);
    }
}
