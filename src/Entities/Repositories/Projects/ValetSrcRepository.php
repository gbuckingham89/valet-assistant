<?php

namespace Gbuckingham89\ValetAssistant\Entities\Repositories\Projects;

use Gbuckingham89\ValetAssistant\Entities\Project;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Illuminate\Support\Collection;
use Valet\Site as ValetSite;

class ValetSrcRepository implements Repository
{
    /**
     * @var \Valet\Site
     */
    protected ValetSite $valetSiteManager;

    /**
     * @param \Valet\Site $valetSiteManager
     */
    public function __construct(ValetSite $valetSiteManager)
    {
        $this->valetSiteManager = $valetSiteManager;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Gbuckingham89\ValetAssistant\Entities\Project>
     */
    public function all(): Collection
    {
        $valetSitesLinked = $this->transformValetSites($this->valetSiteManager->links(), SiteTypeEnum::linked());
        $valetSitesParked = $this->transformValetSites($this->valetSiteManager->parked(), SiteTypeEnum::parked());

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
     * @param \Illuminate\Support\Collection $valetSites
     * @param \Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum $siteType
     *
     * @return \Illuminate\Support\Collection
     */
    protected function transformValetSites(Collection $valetSites, SiteTypeEnum $siteType): Collection
    {
        return $valetSites->groupBy('path')
            ->map(function (Collection $valetSites) use ($siteType): array {
                return $valetSites->map(function (array $valetSite) use ($siteType): Site {
                    return new Site(
                        $siteType,
                        $valetSite['site'],
                        $valetSite['url'],
                        $valetSite['secured']
                    );
                })->toArray();
            });
    }
}
