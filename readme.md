# PHP API

HTTP API backend service for managing two resources and their relations: subscribers and fields

## Prerequisites

This project was developed using PHP 7.1, MySQL 5.7 and Composer

## Installing

 1. Checkout the project
 2. Create a database and import mailerlite.sql in your MySQL
 3. Use Composer to install packages
 4. Rename .env.examplt to .env and set your MySQL configuration
 5. Run command from terminal:

```
php -S 127.0.0.1:8000 -t public
```

## Client

There is a client that can be used to access API, check for the correct URL in case you're running on a different machine than server.
Files to check under client:

```
fields.js
subscribers.js
subscribers_fields.js
```

## Running the tests

To run the PHPUnit Tests

```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/SubscriberTest
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/FieldTest
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/FieldSubscriberTest
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
