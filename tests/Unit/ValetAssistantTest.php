<?php

namespace Gbuckingham89\ValetAssistant\Tests\Unit;

use Gbuckingham89\ValetAssistant\Commander\Commander;
use Gbuckingham89\ValetAssistant\Commander\Outcome;
use Gbuckingham89\ValetAssistant\Entities\Project;
use Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository;
use Gbuckingham89\ValetAssistant\Entities\Site;
use Gbuckingham89\ValetAssistant\Enums\SiteTypeEnum;
use Gbuckingham89\ValetAssistant\Tests\TestCase;
use Gbuckingham89\ValetAssistant\ValetAssistant;
use Illuminate\Support\Collection;

class ValetAssistantTest extends TestCase
{
    public function test_projects()
    {
        $commander = \Mockery::mock(Commander::class);

        $projectRepository = \Mockery::mock(Repository::class);
        $projectRepository->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([
                new Project('/Users/Someone/Code/foo.com', new Collection([
                    new Site(SiteTypeEnum::linked(), 'foo.com', 'https://foo.com.test', true)
                ]))
            ]));

        $valetAssistant = new ValetAssistant($commander, $projectRepository, '/fake/bin/dir');

        $projects = $valetAssistant->projects();

        $this->assertInstanceOf(Collection::class, $projects);
        $this->assertEquals(1, $projects->count());
        $this->assertInstanceOf(Project::class, $projects->first());
    }

    public function test_is_installed()
    {
        $commander = \Mockery::mock(Commander::class);
        $commander->shouldReceive('execute')
            ->with('which valet')
            ->once()
            ->andReturn(new Outcome(['/usr/bin/valet'], 0));

        $projectRepository = \Mockery::mock(Repository::class);

        $valetAssistant = new ValetAssistant($commander, $projectRepository, '/fake/bin/dir');

        $this->assertTrue($valetAssistant->isInstalled());
    }
}
