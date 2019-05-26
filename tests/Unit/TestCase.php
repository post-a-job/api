<?php

declare(strict_types=1);

namespace PostAJob\API;

use League\FactoryMuffin\FactoryMuffin;
use Money\Currency;
use Money\Money;
use PostAJob\API\Job\UseCase\PostNewJob\Command;
use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\ID;
use PostAJob\API\Job\ValueObject\Locations;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;
use ReflectionClass;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected $factoryFaker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->factoryFaker = $this->loadFaker();
        parent::__construct($name, $data, $dataName);
    }

    private function loadFaker(): FactoryMuffin
    {
        $fm = new FactoryMuffin();

        $fm->define(Company::class)->setMaker(function (): Company {
            return new Company('Software Company s.p.a');
        });

        $fm->define(Description::class)->setMaker(function (): Description {
            return new Description('This job is awesome');
        });

        $fm->define(ID::class)->setMaker(function (): ID {
            return ID::fromString('06d0b5eb-e818-4550-951f-c5133de30b01');
        });

        $fm->define(Locations::class)->setMaker(function (): Locations {
            $reflection = new ReflectionClass(Locations::class);
            $constructor = $reflection->getConstructor();
            $constructor->setAccessible(true);
            /** @var Locations $location */
            $location = $reflection->newInstanceWithoutConstructor();
            $constructor->invokeArgs($location, ['Berlin']);

            return $location;
        });

        $fm->define(ProgrammingLanguages::class)->setMaker(function (): ProgrammingLanguages {
            return new ProgrammingLanguages('PHP', 'JAVASCRIPT', 'JAVA');
        });

        $fm->define(Salary::class)->setMaker(function (): Salary {
            return new Salary(
                new Money(1000, new Currency('USD')),
                new Money(10000, new Currency('USD'))
            );
        });

        $fm->define(Title::class)->setMaker(function (): Title {
            return new Title('Senior Software Engineer needed!');
        });

        $fm->define(Command::class)->setMaker(function (): Command {
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

            return new Command($title, $description, $salary, $company, $location, $programmingLanguages);
        });

        return $fm;
    }
}
