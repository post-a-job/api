Feature: See a job
  In order to find a new job
  As an person looking for a job
  I need to see a job application

  Scenario: Server returns a successful response from the see a job API
    Given an HTTP "GET" request to see a job with the URI "http://localhost/jobs/8d47fcdb-48d1-4c4a-92af-7cd80b221468"
    Then the see a job api must reply with a status code 200
    And the see a job api must add a trace ID
    And the see a job api must reply with the body:
    """
    {
        "id": "8d47fcdb-48d1-4c4a-92af-7cd80b221468",
        "title": "Tech Lead",
        "description": "A Tech lead for Google...",
        "salary": {
            "min": "70000 USD",
            "max": "100000 USD"
        },
        "company": "Google",
        "locations": [
            "Berlin", "Milan"
        ],
        "programming_languages": [
            "PHP", "JAVASCRIPT", "JAVA"
        ],
        "posted_at": "2019-08-05 16:09:33",
        "last_update": "2019-08-05 17:00:30"
    }
    """

  Scenario: Server returns a failure response from the see a job API
    Given an HTTP "GET" request to see a job with the URI "http://localhost/jobs/8d47fcdb-48d1-4c4a-92af-7cd80b221469"
    Then the see a job api must reply with a status code 404
    And the see a job api must add a trace ID
    And the see a job api must not reply with a body

