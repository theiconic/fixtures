THE ICONIC Database Fixtures
========
[![Maintainability](https://api.codeclimate.com/v1/badges/c3ae257b2b6ee57de89e/maintainability)](https://codeclimate.com/github/theiconic/fixtures/maintainability)[![Test Coverage](https://api.codeclimate.com/v1/badges/c3ae257b2b6ee57de89e/test_coverage)](https://codeclimate.com/github/theiconic/fixtures/test_coverage)[![Latest Stable Version](https://poser.pugx.org/theiconic/fixtures/v/stable.png)](https://packagist.org/packages/theiconic/fixtures) [![License](https://poser.pugx.org/theiconic/fixtures/license.png)](https://packagist.org/packages/theiconic/fixtures) [![Dependency Status](https://www.versioneye.com/user/projects/54fd3136fcd47a6e740000c7/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54fd3136fcd47a6e740000c7)

## Description

Fixture manager that allows you to easily load your test data fixtures from different formats into your test database.
With an extensible design, it currently support fixtures in the following formats:

* Yaml
* JSON
* MySQL XML Dump

And it currently supports the following databases:

* MySQL
* Redis

The fixture manager also allows you to replace placeholders in your fixtures programatically. See examples below.

## Usage

Suppose that you have two fixtures for your country and employee tables called country.xml and employee.yml, respectively. You load them into a MySQL database with a few lines of code:

```php
use TheIconic\Fixtures\FixtureManager\FixtureManager;

// Declare an array with the path to your fixtures
$fixtures = ['./fixtures/country.xml', './fixtures/employee.yml'];

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

In the values for your fixtures files you can put placeholders instead of real values, this allows you to change the values dynamically, such as, place the current date.

To do this, when creating the Fixture Manager pass an array with the keys in the form "fx:placeholder:<my_name>" inside another array with names of the fixture as key. For example:

```php
use TheIconic\Fixtures\FixtureManager\FixtureManager;

// Declare an array with the path to your fixtures
$fixtures = ['./fixtures/employee.yml'];

// Create a new Fixture Manager passing such array
$fixtureManager = FixtureManager::create(
    $fixtures,
    [
        'employee' => [
            'fx:placeholder:age' => 33
        ]
    ]
);
```

And the employee.xml file would be (notice the value of "age" field):

```xml
<?xml version="1.0"?>
<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<database name="my_database">
	<table_data name="employee">
		<row>
			<field name="id">1</field>
			<field name="name">John Smith</field>
			<field name="age"/>fx:placeholder:age</field>
		</row>
	</table_data>
</database>
</mysqldump>
```

You can pass any value you want, just calculate it before passing it to the create static method.

## Installation

Use Composer to install this package.

```json
{
    "require": {
        "theiconic/fixtures": "~1.5"
    }
}
```

## Fixtures Files Examples

***XML***, use it when you are dealing with fixtures that have NULL values and dates.
```xml
<?xml version="1.0"?>
<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<database name="my_database">
	<table_data name="country">
		<row>
			<field name="id">1</field>
			<field name="name">Australia</field>
			<field name="size" xsi:nil="true" />
		</row>
	</table_data>
</database>
</mysqldump>
```
As an example, you can generate MySQL XML dumps from the commmand line like so
```bash
mysqldump -u <USERNAME> -p<PASSWORD> --xml -t my_database my_table > ~/fixtures/my_table.xml
```

***Yaml***, use it for simple data that does not has NULL values or dates.
```yaml
country:
  -
    id_country: 2
    iso2_code: AD
    iso3_code: AND
    name: Andorra
```

***JSON***
```json
{
  "country": [
    {
      "id_country": 2,
      "iso2_code": "AD",
      "iso3_code": "AND",
      "name": "Andorra"
    }
  ]
}
```

## Contributors

* Jorge A. Borges - Main Developer ([http://jorgeborges.me](http://jorgeborges.me))
* Aaron Weatherall - ([http://www.aaronweatherall.com](http://www.aaronweatherall.com))
* Ollie Brennan - ([http://www.epicarena.com](http://www.epicarena.com/))

## License

THE ICONIC Database Fixtures is released under the MIT License.
