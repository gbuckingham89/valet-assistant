<?php

namespace Gbuckingham89\ValetAssistant\Commander;

class Outcome
{
    /**
     * @var array<int, string>
     */
    protected array $outputLines;

    /**
     * @var int
     */
    protected int $resultCode;

    /**
     * @param array<int, string> $outputLines
     * @param int $resultCode
     */
    public function __construct(array $outputLines, int $resultCode)
    {
        $this->outputLines = $outputLines;
        $this->resultCode = $resultCode;
    }

    /**
     * @return array<int, string>
     */
    public function getOutputLines(): array
    {
        return $this->outputLines;
    }

    /**
     * @return int
     */
    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    /**
     * @return bool
     */
    public function hasOutput(): bool
    {
        return count(array_filter($this->outputLines)) > 0;
    }
}
