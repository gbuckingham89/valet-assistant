<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit\Entities\Repositories\Projects;

use Gbuckingham89\ValetAssistant\Entities\Project;
use Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\ValetSrcRepository;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Gbuckingham89\ValetAssistant\Tests\TestCase;
use Illuminate\Support\Collection;
use Spatie\Enum\Phpunit\EnumAssertions;
use Valet\Site as ValetSite;

class ValetSrcRepositoryTest extends TestCase
{
    public function test_returns_all_projects()
    {
        $valetSiteManager = \Mockery::mock(ValetSite::class);
        $valetSiteManager->shouldReceive('links')
            ->once()
            ->andReturn(new Collection([
                'foo' => [
                    'site' => 'foo',
                    'secured' => '',
                    'url' => 'http://foo.test',
                    'path' => '/Users/Someone/Code/example.com',
                ],
                'bar' => [
                    'site' => 'bar',
                    'secured' => 'X',
                    'url' => 'https://bar.test',
                    'path' => '/Users/Someone/Code/example.com',
                ],
                'demo-project.com' => [
                    'site' => 'demo-project.com',
                    'secured' => '',
                    'url' => 'http://demo-project.com.test',
                    'path' => '/Users/Someone/Code/demosite.com',
                ],
            ]));
        $valetSiteManager->shouldReceive('parked')
            ->once()
            ->andReturn(new Collection([
                'example.com' => [
                    'site' => 'example.com',
                    'secured' => '',
                    'url' => 'http://example.com.test',
                    'path' => '/Users/Someone/Code/example.com',
                ],
                'baz.net' => [
                    'site' => 'baz.net',
                    'secured' => '',
                    'url' => 'http://baz.net.test',
                    'path' => '/Users/Someone/Code/baz.net',
                ],
                'foobar.co.uk' => [
                    'site' => 'foobar.co.uk',
                    'secured' => 'X',
                    'url' => 'https://foobar.co.uk.test',
                    'path' => '/Users/Someone/Code/foobar.co.uk',
                ],
                'super.foo.com' => [
                    'site' => 'super.foo.com',
                    'secured' => 'X',
                    'url' => 'https://super.foo.com.test',
                    'path' => '/Users/SomeoneElse/Entities/super.foo.com',
                ],
            ]));

        $repository = new ValetSrcRepository($valetSiteManager);

        $projects = $repository->all();

        $this->assertInstanceOf(Collection::class, $projects);
        $this->assertEquals(5, $projects->count());

        // PROJECT 0
        $this->assertInstanceOf(Project::class, $projects->get(0));
        $this->assertEquals('/Users/Someone/Code/baz.net', $projects->get(0)->getPath());

        $this->assertInstanceOf(Collection::class, $projects->get(0)->getSites());
        $this->assertEquals(1, $projects->get(0)->getSites()->count());

        $this->assertInstanceOf(Site::class, $projects->get(0)->getSites()->get(0));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::parked(), $projects->get(0)->getSites()->get(0)->getType());
        $this->assertEquals('baz.net', $projects->get(0)->getSites()->get(0)->getHostname());
        $this->assertEquals('http://baz.net.test', $projects->get(0)->getSites()->get(0)->getUrl());
        $this->assertFalse($projects->get(0)->getSites()->get(0)->isSecured());

        // PROJECT 1
        $this->assertInstanceOf(Project::class, $projects->get(1));
        $this->assertEquals('/Users/Someone/Code/demosite.com', $projects->get(1)->getPath());

        $this->assertInstanceOf(Collection::class, $projects->get(1)->getSites());
        $this->assertEquals(1, $projects->get(1)->getSites()->count());

        $this->assertInstanceOf(Site::class, $projects->get(1)->getSites()->get(0));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::linked(), $projects->get(1)->getSites()->get(0)->getType());
        $this->assertEquals('demo-project.com', $projects->get(1)->getSites()->get(0)->getHostname());
        $this->assertEquals('http://demo-project.com.test', $projects->get(1)->getSites()->get(0)->getUrl());
        $this->assertFalse($projects->get(1)->getSites()->get(0)->isSecured());

        // PROJECT 2
        $this->assertInstanceOf(Project::class, $projects->get(2));
        $this->assertEquals('/Users/Someone/Code/example.com', $projects->get(2)->getPath());

        $this->assertInstanceOf(Collection::class, $projects->get(2)->getSites());
        $this->assertEquals(3, $projects->get(2)->getSites()->count());

        $this->assertInstanceOf(Site::class, $projects->get(2)->getSites()->get(0));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::linked(), $projects->get(2)->getSites()->get(0)->getType());
        $this->assertEquals('foo', $projects->get(2)->getSites()->get(0)->getHostname());
        $this->assertEquals('http://foo.test', $projects->get(2)->getSites()->get(0)->getUrl());
        $this->assertFalse($projects->get(2)->getSites()->get(0)->isSecured());

        $this->assertInstanceOf(Site::class, $projects->get(2)->getSites()->get(1));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::linked(), $projects->get(2)->getSites()->get(1)->getType());
        $this->assertEquals('bar', $projects->get(2)->getSites()->get(1)->getHostname());
        $this->assertEquals('https://bar.test', $projects->get(2)->getSites()->get(1)->getUrl());
        $this->assertTrue($projects->get(2)->getSites()->get(1)->isSecured());

        $this->assertInstanceOf(Site::class, $projects->get(2)->getSites()->get(2));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::parked(), $projects->get(2)->getSites()->get(2)->getType());
        $this->assertEquals('example.com', $projects->get(2)->getSites()->get(2)->getHostname());
        $this->assertEquals('http://example.com.test', $projects->get(2)->getSites()->get(2)->getUrl());
        $this->assertFalse($projects->get(2)->getSites()->get(2)->isSecured());

        // PROJECT 3
        $this->assertInstanceOf(Project::class, $projects->get(3));
        $this->assertEquals('/Users/Someone/Code/foobar.co.uk', $projects->get(3)->getPath());

        $this->assertInstanceOf(Collection::class, $projects->get(3)->getSites());
        $this->assertEquals(1, $projects->get(3)->getSites()->count());

        $this->assertInstanceOf(Site::class, $projects->get(3)->getSites()->get(0));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::parked(), $projects->get(3)->getSites()->get(0)->getType());
        $this->assertEquals('foobar.co.uk', $projects->get(3)->getSites()->get(0)->getHostname());
        $this->assertEquals('https://foobar.co.uk.test', $projects->get(3)->getSites()->get(0)->getUrl());
        $this->assertTrue($projects->get(3)->getSites()->get(0)->isSecured());

        // PROJECT 4
        $this->assertInstanceOf(Project::class, $projects->get(4));
        $this->assertEquals('/Users/SomeoneElse/Entities/super.foo.com', $projects->get(4)->getPath());

        $this->assertInstanceOf(Collection::class, $projects->get(4)->getSites());
        $this->assertEquals(1, $projects->get(4)->getSites()->count());

        $this->assertInstanceOf(Site::class, $projects->get(4)->getSites()->get(0));
        EnumAssertions::assertEqualsEnum(SiteTypeEnum::parked(), $projects->get(4)->getSites()->get(0)->getType());
        $this->assertEquals('super.foo.com', $projects->get(4)->getSites()->get(0)->getHostname());
        $this->assertEquals('https://super.foo.com.test', $projects->get(4)->getSites()->get(0)->getUrl());
        $this->assertTrue($projects->get(4)->getSites()->get(0)->isSecured());
    }

    public function test_when_has_no_projects()
    {
        $valetSiteManager = \Mockery::mock(ValetSite::class);
        $valetSiteManager->shouldReceive('links')
            ->once()
            ->andReturn(new Collection());
        $valetSiteManager->shouldReceive('parked')
            ->once()
            ->andReturn(new Collection());

        $repository = new ValetSrcRepository($valetSiteManager);

        $projects = $repository->all();

        $this->assertInstanceOf(Collection::class, $projects);
        $this->assertTrue($projects->isEmpty());
    }
}
