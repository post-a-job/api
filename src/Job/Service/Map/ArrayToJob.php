<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\Map;

use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PostAJob\API\Job\Job;
use PostAJob\API\Job\Service\BuildLocation\BuildLocation;
use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\ID;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;

final class ArrayToJob
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var BuildLocation
     */
    private $buildLocation;

    public function __construct(BuildLocation $buildLocation)
    {
        $this->buildLocation = $buildLocation;
    }

    public function __invoke(array $data): Job
    {
        $salary = \json_decode($data['salary'], true);
        [$minAmount, $minCurrency] = \explode(' ', $salary['min']);
        [$maxAmount, $maxCurrency] = \explode(' ', $salary['max']);

        return new Job(
            ID::fromString($data['id']),
            new Title($data['title']),
            new Description($data['description']),
            new Salary(new Money($minAmount, new Currency($minCurrency)), new Money($maxAmount, new Currency($maxCurrency))),
            new Company($data['company']),
            ($this->buildLocation)(\json_decode($data['locations'], true)),
            new ProgrammingLanguages(...\json_decode($data['programming_languages'], true)),
            DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['posted_at']),
            $data['last_update'] ? DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['last_update']) : null
        );
    }
}
