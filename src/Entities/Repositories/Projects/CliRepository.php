<?php

namespace Gbuckingham89\ValetAssistant\Entities\Repositories\Projects;

use Gbuckingham89\ValetAssistant\Commander\Commander;
use Gbuckingham89\ValetAssistant\Entities\Project;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Gbuckingham89\ValetAssistant\Exceptions\Exception;
use Gbuckingham89\ValetAssistant\Exceptions\ValetNotAvailableException;
use Illuminate\Support\Collection;

class CliRepository implements Repository
{
    /**
     * @var \Gbuckingham89\ValetAssistant\Commander\Commander
     */
    protected Commander $commander;

    /**
     * @param \Gbuckingham89\ValetAssistant\Commander\Commander $commander
     */
    public function __construct(Commander $commander)
    {
        $this->commander = $commander;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Project>
     * @throws \Gbuckingham89\ValetAssistant\Exceptions\Exception
     * @throws \Gbuckingham89\ValetAssistant\Exceptions\ValetNotAvailableException
     */
    public function all(): Collection
    {
        $whichValet = $this->commander->execute('which valet');

        if ($whichValet->getResultCode() !== 0 || !$whichValet->hasOutput()) {
            throw new ValetNotAvailableException();
        }

        $valetSitesLinked = $this->parseSitesFromValetCli(SiteTypeEnum::linked());
        $valetSitesParked = $this->parseSitesFromValetCli(SiteTypeEnum::parked());

        return (new Collection())
            ->mergeRecursive($valetSitesLinked)
            ->mergeRecursive($valetSitesParked)
            ->map(function (array $sites, string $path): Project {
                return new Project($path, new Collection($sites));
            })
            ->sortBy(function (Project $project): string {
                return $project->getName();
            })
            ->values();
    }

    /**
     * @param \Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum $siteType
     *
     * @return \Illuminate\Support\Collection<string, array<int, Site>>
     * @throws \Gbuckingham89\ValetAssistant\Exceptions\Exception
     */
    protected function parseSitesFromValetCli(SiteTypeEnum $siteType): Collection
    {
        switch ($siteType) {
            case SiteTypeEnum::linked():
                $command = 'valet links';
                break;
            case SiteTypeEnum::parked():
                $command = 'valet parked';
                break;
            default:
                throw new Exception('Unsupported SiteTypeENum: ' . $siteType->value);
        }

        $cliOutcome = $this->commander->execute($command);

        if ($cliOutcome->getResultCode() !== 0) {
            throw new Exception(
                'Unexpected result code ' . $cliOutcome->getResultCode() . ' doesn\'t match expected 0 ' .
                'after running command: ' . $command
            );
        }

        $cliOutputLines = new Collection($cliOutcome->getOutputLines());

        if ($cliOutputLines->count() < 5) {
            return new Collection();
        }

        $cliOutputLines->forget([0, 1, 2, $cliOutputLines->keys()->last()]);

        /** @todo Investigate why PHPStan is throwing a type error here */
        /** @phpstan-ignore-next-line */
        return $cliOutputLines
            ->map(function (string $line): array {
                $lineCols = array_map('trim', explode('|', $line));
                return [
                    'path'      => $lineCols[4],
                    'hostname'  => $lineCols[1],
                    'url'       => $lineCols[3],
                    'secured'   => !empty($lineCols[2]),
                ];
            })
            ->groupBy('path')
            ->map(function (Collection $valetSites) use ($siteType): array {
                return $valetSites->map(function (array $valetSiteConfig) use ($siteType): Site {
                    return new Site(
                        $siteType,
                        $valetSiteConfig['hostname'],
                        $valetSiteConfig['url'],
                        $valetSiteConfig['secured']
                    );
                })->toArray();
            });
    }
}
