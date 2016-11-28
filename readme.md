# Uniform API rendering for Laravel 5

[![Latest Stable Version](https://poser.pugx.org/hpolthof/laravel-api/v/stable.svg)](https://packagist.org/packages/hpolthof/laravel-api)
[![License](https://poser.pugx.org/hpolthof/laravel-api/license.svg)](https://packagist.org/packages/hpolthof/laravel-api)
[![Total Downloads](https://poser.pugx.org/hpolthof/laravel-api/d/total.png)](https://packagist.org/packages/hpolthof/laravel-api)

This package was created for an internal project, but as the idea is reusable, I encourage others to make use of this package.

The main goal was to create an uniform way of presenting API output, and the creation of a layer between data structure and output.

## Installation
Require this package with composer:

```
composer require hpolthof/laravel-api
```

Add the follow service provider to your config/app.php:

```
'Hpolthof\LaravelAPI\APIServiceProvider',
```

## Middleware
A new middleware named ```api.errors``` will be added to your list of available middleware.

> If you're using Laravel 5.3, this middleware will also be added into the ```api``` middleware group.

## Usage
To use this package you should implement the ```Hpolthof\LaravelAPI\Contracts\ShouldMorphAPI``` interface
onto an Eloquent model.

You'll have to implement the function ```bindAPI()``` and have to return an instance of ```Hpolthof\LaravelAPI\Binding```.

Like this:

```
public function bindAPI()
{
    return Binding::create([
        'street' => $this->street,
        'street_nr' => $this->number,
        'street_suffix' => $this->suffix,
        'postcode' => $this->zip,
        'city' => $this->city,
    ]);
}
```

In a controller you can then return the following:

```
public function index()
{
    $items = Address::all();
    return \Response::api($items);
}
    
public function show($id)
{
    $item = Address::find($id);
    return \Response::api($item);
}
```

The response would look something like this:

```json
{
    "header": {
        "request": {
            "location": "http:\/\/localhost:8000\/api\/addresses",
            "method": "GET",
            "parameters": []
        },
        "response": {
            "status": 200,
            "error": null,
            "timestamp": "2016-11-28 14:09:35"
        }
    },
    "content": [
        {
            "street": "Van der Polweg",
            "street_nr": 17,
            "street_suffix": "",
            "postcode": "3384HD",
            "city": "Amersfoort",
        },
        {
            "street": "Van der Polweg",
            "street_nr": 15,
            "street_suffix": "",
            "postcode": "3384HD",
            "city": "Amersfoort",
        }
    ]
}
```

## Error messages
Sometimes you'll need to force an error to the user, this can be done by throwing an exception. The package also provides 
some specific exceptions that should be used where relevant.
 
 ```
 Hpolthof\LaravelAPI\Exceptions\AccessDeniedException
 Hpolthof\LaravelAPI\Exceptions\BadRequestException
 Hpolthof\LaravelAPI\Exceptions\NotFoundException
 Hpolthof\LaravelAPI\Exceptions\NotImplementedException
 ```
 
 This would result in something like:
 
 ```json
 {
     "header": {
         "request": {
             "location": "http:\/\/localhost:8000\/api\/addresses",
             "method": "GET",
             "parameters": []
         },
         "response": {
             "status": 403,
             "error": "Forbidden",
             "timestamp": "2016-11-28 15:05:29"
         }
     }
 }
 ```