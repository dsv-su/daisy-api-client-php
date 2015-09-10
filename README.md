Daisy API Client for PHP
========================

This library can be used to access the API provided by DSV's
administrative system Daisy from PHP applications.

Installation
------------

    composer require dsv-su/daisy-api-client-php '~1.0'

Usage
-----

Create a file `daisy_api.json` containing

```json
{
    "url": "https://api.dsv.su.se/rest/",
    "user": "nisse",
    "pass": "password"
}
```

except with user and pass replaced by yours. Now you can use the
library like this:

```php
require 'vendor/autoload.php';
use DsvSu\Daisy;

$employees = Daisy\Employee::find(['department' => 4]);
foreach ($employees as $e) {
    $p = $e->getPerson();
    echo $p->getFirstName() . ' ' . $p->getLastName() . "\n";
}
```

If `daisy_api.json` exists in the current directory, it will be used
automatically. Otherwise, you can specify its location with:

    Daisy\Client::initUsingConfigFile(dirname(__FILE__) . '/daisy_api.json');

Documentation
-------------

Some functions are documented in the source code using phpdoc
comments. The easiest way to understand how to use the library is to
read the source and look at examples,
e.g. https://github.com/dsv-su/daisyweb.

Test suite
----------

The library has a test suite which you should run before committing
anything:

    vendor/bin/phpunit

It is a good idea to add test cases when you modify the code.
