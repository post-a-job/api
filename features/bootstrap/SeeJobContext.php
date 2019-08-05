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
class SeeJobContext implements Context
{
    private const EXISTING_ID = '8d47fcdb-48d1-4c4a-92af-7cd80b221468';

    private const NOT_FOUND_ID = '8d47fcdb-48d1-4c4a-92af-7cd80b221469';

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @BeforeSuite
     */
    public static function ensureDatabaseState(): void
    {
        $connection = Utils::getConnection();

        // Ensure NOT_FOUND_ID is not in the database
        $stmt = $connection->prepare('DELETE FROM jobs WHERE id = ?');
        $stmt->execute([self::NOT_FOUND_ID]);

        // Ensure EXISTING_ID is not in the database with wrong data
        $stmt = $connection->prepare('DELETE FROM jobs WHERE id = ?');
        $stmt->execute([self::EXISTING_ID]);

        // Ensure EXISTING_ID is in the database
        $stmt = $connection->prepare(<<<EOF
INSERT INTO jobs (id,title,description,company,locations,programming_languages,salary,posted_at,last_update)
VALUES (?,?,?,?,?,?,?,?,?)
EOF
        );
        $stmt->execute([
            self::EXISTING_ID,
            'Tech Lead',
            'A Tech lead for Google...',
            'Google',
            '["Berlin","Milan"]',
            '["PHP","JAVASCRIPT","JAVA"]',
            '{"min":"70000 USD","max":"100000 USD"}',
            '2019-08-05 16:09:33',
            '2019-08-05 17:00:30',
        ]);
    }

    /**
     * @Given an HTTP :arg1 request to see a job with the URI :arg2
     */
    public function anHttpRequestToSeeAJobWithTheUri(string $method, string $uri): void
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
     * @Then the see a job api must reply with a status code :arg1
     */
    public function theSeeAJobApiMustReplyWithAStatusCode(int $expectedStatusCode): void
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode !== $expectedStatusCode) {
            throw new Exception(
                "The status code is not the expected one. Expected: $expectedStatusCode. Given: $statusCode"
            );
        }
    }

    /**
     * @Then the see a job api must add a trace ID
     */
    public function theSeeAJobApiMustAddATraceId(): void
    {
        if ($this->response->getHeader('X-Trace-ID') === []) {
            throw new Exception('The trace ID is not filled');
        }
    }

    /**
     * @Then the see a job api must reply with the body:
     */
    public function theSeeAJobApiMustReplyWithTheBody(PyStringNode $expectedBody): void
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
     * @Then the see a job api must not reply with a body
     */
    public function theSeeAJobApiMustNotReplyWithABody(): void
    {
        $body = (string) $this->response->getBody();
        if ('' !== $body) {
            throw new Exception(
                "The response body is not empty the expected one. Given: $body."
            );
        }
    }
}
