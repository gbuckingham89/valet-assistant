<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit\Entities;

use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Tests\TestCase;
use Spatie\Enum\Phpunit\EnumAssertions;

class SiteTest extends TestCase
{
    public function test_get_type()
    {
        $site = new Site(SiteTypeEnum::linked(), 'example.com', 'http://example.com.test', false);

        EnumAssertions::assertEqualsEnum(SiteTypeEnum::linked(), $site->getType());
    }

    public function test_get_hostname()
    {
        $site = new Site(SiteTypeEnum::linked(), 'example.com', 'http://example.com.test', false);

        $this->assertEquals('example.com', $site->getHostname());
    }

    public function test_get_url()
    {
        $site = new Site(SiteTypeEnum::linked(), 'example.com', 'http://example.com.test', false);

        $this->assertEquals('http://example.com.test', $site->getUrl());
    }

    public function test_is_secured()
    {
        $site1 = new Site(SiteTypeEnum::linked(), 'example.com', 'http://example.com.test', false);

        $this->assertFalse($site1->isSecured());

        $site2 = new Site(SiteTypeEnum::linked(), 'example.com', 'https://example.com.test', true);

        $this->assertTrue($site2->isSecured());
    }
}
