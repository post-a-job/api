-- migrate:up
CREATE EXTENSION "uuid-ossp";

-- migrate:down
DROP EXTENSION "uuid-ossp";
