<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsDuplicated;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsEmpty;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsNotSupported;

final class ProgrammingLanguages
{
    private const PHP = 'PHP';
    private const JAVA = 'JAVA';
    private const JAVASCRIPT = 'JAVASCRIPT';
    private const GOLANG = 'GOLANG';

    private const SUPPORTED_LANGUAGES = [self::PHP, self::JAVA, self::JAVASCRIPT, self::GOLANG];

    /**
     * @var string[]
     */
    private $programmingLanguages;

    /**
     * @throws ProgrammingLanguagesIsNotSupported
     * @throws ProgrammingLanguagesIsDuplicated
     */
    public function __construct(string ...$programmingLanguages)
    {
        $map = [];
        foreach ($programmingLanguages as $programmingLanguage) {
            if (!\in_array($programmingLanguage, self::SUPPORTED_LANGUAGES, true)) {
                throw new ProgrammingLanguagesIsNotSupported($programmingLanguage);
            }
            if (\in_array($programmingLanguage, $map, true)) {
                throw new ProgrammingLanguagesIsDuplicated($programmingLanguage);
            }
            $map[] = $programmingLanguage;
        }

        if (0 === \count($programmingLanguages)) {
            throw new ProgrammingLanguagesIsEmpty();
        }

        $this->programmingLanguages = $programmingLanguages;
    }

    public function toArray(): array
    {
        return $this->programmingLanguages;
    }

    public function equals(self $collection): bool
    {
        if (\count($this->programmingLanguages) !== \count($collection->programmingLanguages)) {
            return false;
        }

        $map = [];
        foreach ($this->programmingLanguages as $programmingLanguage) {
            $map[] = $programmingLanguage;
        }

        foreach ($collection->programmingLanguages as $programmingLanguage) {
            if (!\in_array($programmingLanguage, $map, true)) {
                return false;
            }
        }

        return true;
    }

    private function __clone()
    {
    }
}
