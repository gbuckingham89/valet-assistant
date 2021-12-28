<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit\Entities;

use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Gbuckingham89\ValetAssistant\Entities\Project;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Tests\TestCase;
use Illuminate\Support\Collection;

class ProjectTest extends TestCase
{
    public function test_get_sites()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->assertInstanceOf(Collection::class, $project->getSites());
        $this->assertEquals(1, $project->getSites()->count());
        $this->assertInstanceOf(Site::class, $project->getSites()->first());
    }

    public function test_get_name()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->assertEquals('example.com', $project->getName());
    }

    public function test_get_path()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->assertEquals('/Users/JohnSmith/Code/example.com', $project->getPath());
    }

    public function test_get_php_semver_constraint()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->mock('files', function ($mock) {
            $mock->shouldReceive('exists')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('get')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn('{ "name": "foo/bar", "require": { "php": "^7.3|^8.0", "ext-json": "*", "illuminate/support": "^8.77" } }');
        });

        $this->assertEquals('^7.3|^8.0', $project->getPhpSemverConstraint());
    }

    public function test_get_php_semver_constraint_when_composer_json_doesnt_have_it()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->mock('files', function ($mock) {
            $mock->shouldReceive('exists')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('get')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn('{ "name": "foo/bar", "require": { "ext-json": "*", "illuminate/support": "^8.77" } }');
        });

        $this->assertNull($project->getPhpSemverConstraint());
    }

    public function test_get_php_semver_constraint_when_composer_json_is_empty()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->mock('files', function ($mock) {
            $mock->shouldReceive('exists')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('get')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn('');
        });

        $this->assertNull($project->getPhpSemverConstraint());
    }

    public function test_get_php_semver_constraint_when_composer_json_doesnt_exist()
    {
        $project = new Project('/Users/JohnSmith/Code/example.com', new Collection([
            new Site(SiteTypeEnum::parked(), 'foo.com', 'https://foo.com.test', true)
        ]));

        $this->mock('files', function ($mock) {
            $mock->shouldReceive('exists')
                ->with('/Users/JohnSmith/Code/example.com/composer.json')
                ->once()
                ->andReturn(false);
        });

        $this->assertNull($project->getPhpSemverConstraint());
    }
}
