<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Defines application features from the specific context.
 */
class JobContext implements Context
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var PyStringNode
     */
    private $body;

    /**
     * @Given an HTTP :arg1 request with the URI :arg2 with the body
     */
    public function anHttpRequestWithTheUriWithTheBody(string $method, string $uri, PyStringNode $body): void
    {
        $client = new Client();
        try {
            $response = $client->request($method, $uri, ['body' => (string) $body]);
        } catch (ClientException $e) {
            if (($response = $e->getResponse()) && null === $response) {
                throw new Exception("The client failed during the HTTP call due to: {$e->getMessage()}");
            }
        }
        $this->body = $body;
        $this->response = $response;
    }

    /**
     * @Then the post a job api must reply with a status code :arg1
     */
    public function thePostAJobApiMustReplyWithAStatusCode(int $expectedStatusCode): void
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode !== $expectedStatusCode) {
            throw new Exception(
                "The status code is not the expected one. Expected: $statusCode. Given: $expectedStatusCode"
            );
        }
    }

    /**
     * @Then the post a job api must add a trace ID
     */
    public function thePostAJobApiMustAddATraceId(): void
    {
        if ($this->response->getHeader('X-Trace-ID') === []) {
            throw new Exception('The trace ID is not filled');
        }
    }

    /**
     * @Then the post a job api must not reply with a body
     */
    public function thePostAJobApiMustNotReplyWithABody(): void
    {
        $body = (string) $this->response->getBody();
        if ('' !== $body) {
            throw new Exception(
                "The response body is not empty the expected one. Given: $body."
            );
        }
    }

    /**
     * @Then the post a job api must add a location header with the job ID
     */
    public function thePostAJobApiMustAddALocationHeaderWithTheJobId(): void
    {
        $locationHeaders = $this->response->getHeader('Location');
        if (\count($locationHeaders) < 1) {
            throw new Exception('The location is not the filled');
        }

        try {
            $this->uuid = Uuid::fromString(\str_replace('/jobs/', '', $locationHeaders[0]));
        } catch (Throwable $e) {
            throw new Exception("The location header content is not a valid uuid. Given: {$locationHeaders[0]}.");
        }
    }

    /**
     * @Then the database must have a record about the new job
     */
    public function theDatabaseMustHaveARecordAboutTheNewJob(): void
    {
        $connection = Utils::getConnection();
        $statement = $connection->prepare('SELECT * FROM jobs WHERE id = :id');
        $statement->bindValue(':id', $this->uuid);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row['id'] !== (string) $this->uuid) {
            throw new RuntimeException("id doesn't match. Given:{$this->uuid}. Stored:{$row['id']}");
        }

        $salary = \json_decode($row['salary'], true);
        [$min,] = \explode(' ', $salary['min']);
        [$max, $currency] = \explode(' ', $salary['max']);

        $storedData = [
            'title' => $row['title'],
            'company' => $row['company'],
            'locations' => \json_decode($row['locations'], true),
            'description' => $row['description'],
            'programming_languages' => \json_decode($row['programming_languages'], true),
            'salary' => [
                'currency' => $currency,
                'min' => (int) $min,
                'max' => (int) $max,
            ],
        ];

        $storedData = \json_encode($storedData);
        // Remove new line and formatting white spaces
        $body = \json_encode(\json_decode((string) $this->body));

        if ($storedData !== $body) {
            throw new RuntimeException("Mismatch between data sent and stored. Given:$body. Stored:$storedData.");
        }
    }

    /**
     * @Then the post a job api must reply with the body:
     */
    public function thePostAJobApiMustReplyWithTheBody(PyStringNode $expectedBody)
    {
        $body = \json_encode(\json_decode((string) $this->response->getBody(), true));
        $expectedBody = \json_encode(\json_decode((string) $expectedBody, true));
        if ($expectedBody !== $body) {
            throw new Exception(
                "The response body is not the expected one. Expected:$expectedBody. Given:$body."
            );
        }
    }

    /**
     * @Then the post a job api must not add a location header with the job ID
     */
    public function thePostAJobApiMustNotAddALocationHeaderWithTheJobId()
    {
        if ($this->response->getHeader('Location') !== []) {
            throw new Exception(
                'The location header must not be in place.'
            );
        }
    }
}
