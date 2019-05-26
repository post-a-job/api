<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\PostNewJob;

use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\Locations;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;
use PostAJob\API\TestCase;

final class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_the_same_properties_that_have_been_injected(): void
    {
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

        $command = new Command($title, $description, $salary, $company, $location, $programmingLanguages);
        $this->assertSame($title, $command->title());
        $this->assertSame($description, $command->description());
        $this->assertSame($salary, $command->salary());
        $this->assertSame($company, $command->company());
        $this->assertSame($location, $command->locations());
        $this->assertSame($programmingLanguages, $command->programmingLanguages());
    }
}
