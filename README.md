THE ICONIC Database Fixtures
========
[![Build Status](https://travis-ci.org/theiconic/fixtures.png?branch=v1.2.3)](https://travis-ci.org/theiconic/fixtures) [![Coverage Status](https://coveralls.io/repos/theiconic/fixtures/badge.png?branch=master)](https://coveralls.io/r/theiconic/fixtures?branch=master)

## Description

Fixture manager that allows you to easily load your test data fixtures from different formats into your test database.
With an extensible design, it currently support fixtures in the following formats:

* Yaml
* MySQL XML Dump

And it currently supports the following databases:

* MySQL

## Usage

Suppose that you have two fixtures for your country and employee tables called country.xml and employee.yml, respectively. You load them into a MySQL database with a few lines of code:

```php
use TheIconic\Fixtures\FixtureManager\FixtureManager;

// Declare an array with the path to your fixtures
$fixtures = ['./fixtures/country.xml', '.fixtures/employee.yml'];

// Create a new Fixture Manager passing such array
$fixtureManager = FixtureManager::create($fixtures);

// Persist the fixtures into your test database as follow
// Create a Default PDO Persister (currently MySQL is default)
// Here you pass host, database name, username and password
$fixtureManager->setDefaultPDOPersister('127.0.0.1', 'test_database', 'root', '123abc');

// Finally, insert fixtures into database, each table will be cleaned before insertion
$fixtureManager->persist();

// You may clean your database if needed at any point doing
$fixtureManager->cleanStorage();
```

That's it!

Also, FixtureManager has a fluent interface, this means you can chain method calls like...

```php
$fixtureManager
    ->setDefaultPDOPersister('127.0.0.1', 'test_database', 'root', '123abc')
    ->cleanStorage()
    ->persist();
```

## Installation

Use Composer to install this package.

``` json
{
    "require": {
        "theiconic/fixtures": "~1.2"
    }
}
```

## Contributors

* Jorge A. Borges - Main Developer ([http://jorgeborges.me](http://jorgeborges.me))

## License

THE ICONIC Database Fixtures is released under the MIT License.
