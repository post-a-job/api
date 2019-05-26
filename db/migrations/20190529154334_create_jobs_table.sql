-- migrate:up
CREATE TABLE jobs(
   id uuid PRIMARY KEY NOT NULL,
   title VARCHAR(255) NOT NULL DEFAULT NULL,
   description VARCHAR(255) DEFAULT NULL,
   company VARCHAR(255) NOT NULL DEFAULT NULL,
   locations JSON NOT NULL DEFAULT NULL,
   programming_languages JSON NOT NULL DEFAULT NULL,
   salary JSON NOT NULL DEFAULT NULL,
   posted_at TIMESTAMP NOT NULL DEFAULT NULL,
   last_update TIMESTAMP DEFAULT NULL
);

-- migrate:down
DROP TABLE  jobs