<?php

namespace Gbuckingham89\ValetAssistant\Entities;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Project
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * @var \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Site>
     */
    protected Collection $sites;

    /**
     * @param string $path
     * @param \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Site> $sites
     */
    public function __construct(string $path, Collection $sites)
    {
        $this->path = $path;
        $this->sites = $sites;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return basename($this->path);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getPhpSemverConstraint(): ?string
    {
        $composerJson = $this->composerJson();

        if ($composerJson === null) {
            return null;
        }

        $constraint = data_get(json_decode($composerJson, true), 'require.php');

        return $constraint !== null && $constraint !== '' ? strval($constraint) : null;
    }

    /**
     * @return string|null
     */
    protected function composerJson(): ?string
    {
        $path = rtrim($this->getPath(), '/') . '/composer.json';

        if (!File::exists($path)) {
            return null;
        }

        $composerJson = File::get($path);

        return !empty($composerJson) ? $composerJson : null;
    }
}
