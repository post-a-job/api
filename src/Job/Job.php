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

final class Job
{
    /**
     * @var ID
     */
    private $id;

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
    private $location;

    /**
     * @var ProgrammingLanguages
     */
    private $programmingLanguages;

    /**
     * @var DateTimeImmutable
     */
    private $postedAt;

    /**
     * @var DateTimeImmutable|null
     */
    private $lastUpdate;

    public function __construct(
        ID $id,
        Title $title,
        Description $description,
        Salary $salary,
        Company $company,
        Locations $location,
        ProgrammingLanguages $programmingLanguages,
        DateTimeImmutable $postedAt,
        ?DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->salary = $salary;
        $this->company = $company;
        $this->location = $location;
        $this->programmingLanguages = $programmingLanguages;
        $this->postedAt = $postedAt;
        $this->lastUpdate = $updatedAt;
    }

    public static function post(
        ID $id,
        Title $title,
        Description $description,
        Salary $salary,
        Company $company,
        Locations $location,
        ProgrammingLanguages $programmingLanguages
    ): self {
        $postedAt = new DateTimeImmutable();

        return new self($id, $title, $description, $salary, $company, $location, $programmingLanguages, $postedAt, null);
    }

    public function id(): ID
    {
        return $this->id;
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

    public function location(): Locations
    {
        return $this->location;
    }

    public function programmingLanguages(): ProgrammingLanguages
    {
        return $this->programmingLanguages;
    }

    public function postedAt(): DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function lastUpdate(): ?DateTimeImmutable
    {
        return $this->lastUpdate;
    }
}
