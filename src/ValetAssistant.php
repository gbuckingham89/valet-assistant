<?php

namespace Gbuckingham89\ValetAssistant;

use Gbuckingham89\ValetAssistant\Commander\Commander;
use Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository;
use Illuminate\Support\Collection;

class ValetAssistant
{
    /**
     * @var \Gbuckingham89\ValetAssistant\Commander\Commander
     */
    protected Commander $commander;

    /**
     * @var \Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository
     */
    protected Repository $projectsRepository;

    /**
     * @var string|null
     */
    protected ?string $path;

    /**
     * @param \Gbuckingham89\ValetAssistant\Commander\Commander $commander
     * @param \Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository $projectsRepository
     */
    public function __construct(Commander $commander, Repository $projectsRepository)
    {
        $this->commander = $commander;
        $this->projectsRepository = $projectsRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function projects(): Collection
    {
        return $this->projectsRepository->all();
    }

    /**
     * @return bool
     */
    public function isInstalled(): bool
    {
        $whichValet = $this->commander->execute('which valet');

        return $whichValet->getResultCode() === 0 && $whichValet->hasOutput();
    }
}
