<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use Money\Currency;
use Money\Money;
use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Exception\SalaryHasDifferentCurrencies;
use PostAJob\API\Job\ValueObject\Exception\SalaryMinIsGreaterThenMax;
use PostAJob\API\Job\ValueObject\Salary as SalaryVO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Salary implements MiddlewareInterface
{
    private const KEY = 'salary';
    private const MIN = 'min';
    private const MAX = 'max';
    private const CURRENCY = 'currency';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = \json_decode((string) $request->getBody(), true) ?: [];
        try {
            $currency = new Currency($body[self::KEY][self::CURRENCY] ?? '');
        } catch (\InvalidArgumentException $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY][self::CURRENCY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);
        }
        try {
            $min = new Money($body[self::KEY][self::MIN] ?? '', $currency ?? new Currency('USD'));
        } catch (\InvalidArgumentException $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY][self::MIN] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);
        }

        try {
            $max = new Money($body[self::KEY][self::MAX] ?? '', $currency ?? new Currency('USD'));
        } catch (\InvalidArgumentException $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY][self::MAX] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);
        }

        if (!isset($min, $max, $currency)) {
            return $handler->handle($request);
        }

        try {
            $salary = new SalaryVO($min, $max);
        } catch (SalaryMinIsGreaterThenMax | SalaryHasDifferentCurrencies $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(SalaryVO::class, $salary);

        return $handler->handle($request);
    }
}
