## Installation

1. run `composer update`. from project root directory
2. add app.php file

Cacheing configuraton 

1. Please add the following lines on app.php file in 

 ```
 'performers' => [
    'className' => 'File',
    'path' => CACHE.'performers/',
    'serialize' => true,
    'duration' => '+1 hours',
    'url' => env('CACHE_CAKECORE_URL', null),
 ]
```
