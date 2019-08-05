<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\IDIsInvalid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ID
{
    /**
     * @var UuidInterface
     */
    private $value;

    private function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @throws IDIsInvalid
     */
    public static function fromString(string $value): self
    {
        try {
            return new self(Uuid::fromString($value));
        } catch (InvalidUuidStringException $e) {
            throw new IDIsInvalid($value, $e);
        }
    }

    public function value(): string
    {
        return $this->value->toString();
    }

    public function __toString(): string
    {
        return $this->value->toString();
    }

    public function equals(self $ID): bool
    {
        return $this->value->equals($ID->value);
    }

    private function __clone()
    {
    }
}
