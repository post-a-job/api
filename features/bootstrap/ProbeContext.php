<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class ProbeContext implements Context
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @Given an HTTP :arg1 request with the URI :arg2
     */
    public function anHttpRequestWithTheUri(string $method, string $uri): void
    {
        $client = new Client();
        try {
            $response = $client->request($method, $uri);
        } catch (ClientException $e) {
            if (($response = $e->getResponse()) && null === $response) {
                throw new Exception("The client failed during the HTTP call due to: {$e->getMessage()}");
            }
        }
        $this->response = $response;
    }

    /**
     * @Then the server must reply with a status code :arg1
     */
    public function theServerMustReplyWithAStatusCode(int $expectedStatusCode): void
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode !== $expectedStatusCode) {
            throw new Exception(
                "The status code is not the expected one. Expected: $statusCode. Given: $expectedStatusCode"
            );
        }
    }

    /**
     * @Then the server must add a trace ID
     */
    public function theServerMustAddATraceId(): void
    {
        if ($this->response->getHeader('X-Trace-ID') === []) {
            throw new Exception('The trace ID is not filled');
        }
    }

    /**
     * @Then the server must reply with a body:
     */
    public function theServerMustReplyWithABody(PyStringNode $expectedBody): void
    {
        $body = (string) $this->response->getBody();
        if ($body !== (string) $expectedBody) {
            throw new Exception(
                "The response body is not the expected one. Expected: $expectedBody. Given: $body."
            );
        }
    }

    /**
     * @Given an HTTP :arg1 request with the URI :arg2 and a trace ID :arg3
     */
    public function anHttpRequestWithTheUriAndATraceId(string $method, string $uri, string $traceID): void
    {
        $client = new Client();
        try {
            $response = $client->request($method, $uri, ['headers' => ['X-Trace-ID' => $traceID]]);
        } catch (ClientException $e) {
            if (($response = $e->getResponse()) && null === $response) {
                throw new Exception("The client failed during the HTTP call due to: {$e->getMessage()}");
            }
        }
        $this->response = $response;
    }

    /**
     * @Then the server must reply with the trace ID :arg1
     */
    public function theServerMustReplyWithTheTraceId(string $expectedTraceID): void
    {
        $traceHeaders = $this->response->getHeader('X-Trace-ID');
        if (\count($traceHeaders) < 1) {
            throw new Exception('The trace id is not the filled');
        }

        if ($traceHeaders[0] !== $expectedTraceID) {
            throw new Exception("The trace id is not the expected one. Expected: $expectedTraceID. Given: {$traceHeaders[0]}.");
        }
    }
}
