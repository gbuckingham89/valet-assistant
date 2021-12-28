<?php

namespace Gbuckingham89\ValetAssistant\Entities;

use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;

class Site
{
    /**
     * @var \Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum
     */
    protected SiteTypeEnum $type;

    /**
     * @var string
     */
    protected string $hostname;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var bool
     */
    protected bool $secured;

    /**
     * @param \Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum $type
     * @param string $hostname
     * @param string $url
     * @param bool $secured
     */
    public function __construct(SiteTypeEnum $type, string $hostname, string $url, bool $secured)
    {
        $this->type = $type;
        $this->hostname = $hostname;
        $this->url = $url;
        $this->secured = $secured;
    }

    /**
     * @return \Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum
     */
    public function getType(): SiteTypeEnum
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isSecured(): bool
    {
        return $this->secured;
    }
}
