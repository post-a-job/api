Feature: Post a job
  In order to find a new employee
  As an employer
  I need to post a job application

  Scenario: Server returns a successful response the from post a job API
    Given an HTTP "POST" request with the URI "http://localhost/jobs/" with the body
    """
    {
       "title":"Chapter Lead",
       "company":"Google",
       "locations":[
          "Berlin",
          "Milan"
       ],
       "description":"A Tech lead for Google...",
       "programming_languages":["PHP", "GOLANG"],
       "salary":{
          "currency":"USD",
          "min":70000,
          "max":100000
       }
    }
    """
    Then the post a job api must reply with a status code 201
    And the post a job api must add a trace ID
    And the post a job api must not reply with a body
    And the post a job api must add a location header with the job ID
    And the database must have a record about the new job

  Scenario: Server returns a failure response the from post a job API when an empty request body
    Given an HTTP "POST" request with the URI "http://localhost/jobs/" with the body
    """
    """
    Then the post a job api must reply with a status code 400
    And the post a job api must add a trace ID
    And the post a job api must reply with the body:
    """
    {
       "company":"The company must not be empty.",
       "locations":"The locations must exists. The given locations \"\" does not exists. If it exists contact us.",
       "programming_languages":"The programming language must not be empty.",
       "salary":{
          "currency":"Currency code should not be empty string",
          "min":"Empty number is invalid",
          "max":"Empty number is invalid"
       },
       "title":"The title must not be empty."
    }
    """
    And the post a job api must not add a location header with the job ID


  Scenario: Server returns a failure response the from post a job API when a request body has all wrong (and too short) params
    Given an HTTP "POST" request with the URI "http://localhost/jobs/" with the body
    """
    {
      "title":"A",
      "company":"B",
      "locations":[],
      "description": "C",
      "programming_languages":["RAMBOMAMBO"],
      "salary":{
        "currency":"",
        "min":"invalid",
        "max":"invalid"
      }
    }
    """
    Then the post a job api must reply with a status code 400
    And the post a job api must add a trace ID
    And the post a job api must reply with the body:
    """
    {
      "company":"The company must be at least 2 characters.",
      "locations":"The locations must exists. The given locations \"\" does not exists. If it exists contact us.",
      "programming_languages":"The programming language must be supported. The given programming language \"RAMBOMAMBO\" is not supported yet.",
      "salary":{
        "currency":"Currency code should not be empty string",
        "min":"Invalid integer part invalid. Invalid digit i found",
        "max":"Invalid integer part invalid. Invalid digit i found"
      },
      "title":"The title must be at least 5 characters."
    }
    """
    And the post a job api must not add a location header with the job ID

  Scenario: Server returns a failure response the from post a job API when a request body has all wrong (and too long) params
    Given an HTTP "POST" request with the URI "http://localhost/jobs/" with the body
    """
    {
      "title":"AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA",
      "company":"BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB",
      "locations":["NOT EXISTING"],
      "description": "CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC",
      "programming_languages":["RAMBOMAMBO"],
      "salary":{
        "currency":"USD",
        "min":0,
        "max":0
      }
    }
    """
    Then the post a job api must reply with a status code 400
    And the post a job api must add a trace ID
    And the post a job api must reply with the body:
    """
    {
      "company":"The company must be max 255 characters.",
      "description":"The description must be max 255 characters.",
      "locations":"The locations must exists. The given locations \"NOT EXISTING\" does not exists. If it exists contact us.",
      "programming_languages":"The programming language must be supported. The given programming language \"RAMBOMAMBO\" is not supported yet.",
      "title":"The title must be max 255 characters."
    }
    """
    And the post a job api must not add a location header with the job ID


