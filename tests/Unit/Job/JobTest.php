<?php

declare(strict_types=1);

namespace PostAJob\API\Job;

use DateTimeImmutable;
use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\ID;
use PostAJob\API\Job\ValueObject\Locations;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;
use PostAJob\API\TestCase;

final class JobTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_the_same_properties_that_have_been_injected(): void
    {
        /** @var ID $id */
        $id = $this->factoryFaker->instance(ID::class);
        /** @var Title $title */
        $title = $this->factoryFaker->instance(Title::class);
        /** @var Description $description */
        $description = $this->factoryFaker->instance(Description::class);
        /** @var Salary $salary */
        $salary = $this->factoryFaker->instance(Salary::class);
        /** @var Company $company */
        $company = $this->factoryFaker->instance(Company::class);
        /** @var Locations $location */
        $location = $this->factoryFaker->instance(Locations::class);
        /** @var ProgrammingLanguages $programmingLanguages */
        $programmingLanguages = $this->factoryFaker->instance(ProgrammingLanguages::class);

        $job = Job::post($id, $title, $description, $salary, $company, $location, $programmingLanguages);
        $this->assertSame($id, $job->id());
        $this->assertSame($title, $job->title());
        $this->assertSame($description, $job->description());
        $this->assertSame($salary, $job->salary());
        $this->assertSame($company, $job->company());
        $this->assertSame($location, $job->location());
        $this->assertSame($programmingLanguages, $job->programmingLanguages());
        $this->assertEqualsWithDelta(new DateTimeImmutable(), $job->postedAt(), 5);
        $this->assertNull($job->lastUpdate());
    }
}
