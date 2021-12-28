<?php

namespace Gbuckingham89\ValetAssistant\Entities\Repositories\Projects;

use Illuminate\Support\Collection;

interface Repository
{
    /**
     * @return \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Project>
     */
    public function all(): Collection;
}
