<?php

namespace Gbuckingham89\ValetAssistant\Exceptions;

class ValetNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Valet is unavailable. Please ensure it\'s installed ' .
            'and that the binary is accessible by the user running PHP.'
        );
    }
}
