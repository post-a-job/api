<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\PostNewJob;

use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\Locations;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;

final class Command
{
    /**
     * @var Title
     */
    private $title;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var Salary
     */
    private $salary;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var Locations
     */
    private $locations;

    /**
     * @var ProgrammingLanguages
     */
    private $programmingLanguages;

    public function __construct(
        Title $title,
        Description $description,
        Salary $salary,
        Company $company,
        Locations $locations,
        ProgrammingLanguages $programmingLanguages
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->salary = $salary;
        $this->company = $company;
        $this->locations = $locations;
        $this->programmingLanguages = $programmingLanguages;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function salary(): Salary
    {
        return $this->salary;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function locations(): Locations
    {
        return $this->locations;
    }

    public function programmingLanguages(): ProgrammingLanguages
    {
        return $this->programmingLanguages;
    }
}
