nexxOMNIA API Client
=====================

The nexxOMNIA API Client provides easy Access to all API Endpoints of the nexxOMNIA API.

Getting Started
---------------

```
$ composer require nexxomnia/apiclient-php
```

```php
use nexxomnia\apiclient;
use nexxomnia\apicalls\media;
use nexxomnia\enums\streamtypes;

$apiclient = new apiclient();
$apiclient->configure(999,"API-SECRET","SESSION-ID");

$apicall = new media(streamtypes::VIDEO);
$apicall->latest();

$result=$apiclient->call($apicall);

echo $result->getResultIterator(TRUE)->current()->getGeneral()->getID(); // outputs the ID of the first Element

```

Resources
---------

Please find all further Documentation and Usage Examples [here](https://api.docs.nexx.cloud/api-clients/php).

