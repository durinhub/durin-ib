CREATE DATABASE IF NOT EXISTS imageboarddb;

CREATE USER imageboarduser@localhost identified by '12345678';

GRANT ALL ON imageboarddb.* TO imageboarduser@localhost WITH GRANT OPTION;