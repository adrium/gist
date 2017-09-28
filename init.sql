CREATE TABLE users (
	userid INT KEY,
	username VARCHAR(127),
	password VARCHAR(127),
	externalkey VARCHAR(127)
);

INSERT INTO users VALUES
(1, 'test', '$2y$10$EuAahVWXP.rLO9Ucsc6SweLnn.tWFnxxjaa4BpbHfABaF/daNz0C6', 'testkey');
