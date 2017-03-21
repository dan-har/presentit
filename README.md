# presentit

Presentit is an easy to use tool for custom presentations of nested data structures that are used in API responses.

```php
Present::item($user)->with(function (User $user) {
    return [
        'id' => $user->id,
        'name' => $user->first_name . " " . $user->last_name,
        'image' => $user->image ?: Present::hidden(),
        'friends' => Present::each($user->friends)->with(function (User $friend) {
            return [
                //...
            ]
        })
    ];
})->show();
```
## Features

+ Fluent interface to transform nested data structures
+ Transformer classes for presentation logic code reuse.
+ Callback transformers for inline data transformations. 
+ Control data properties visibility using the Hidden object instead of using if statements.

## Adapters

+ [Laravel framework adapter](https://github.com/dan-har/presentit-laravel)

# Docs
+ [Installation](#installation)
+ [Basic usage](#basic-usage)

## Installation

Install using composer

```
composer require dan-har/presentit
```

## Basic usage
 
The ```Present``` class is used for transforming single resource or a collection of resources.

Present a single resource by using the ```item()``` method. 

```php
$presentation = Present::item($user)->with(function (User $user) {
    return [
        'id' => $user->id,
        'first_name' => ucfirst($user->first_name),
        // ...
    ];
});
```

Present a list of resources using the ```collection``` or ```each``` methods.
Each of the items in the list will be transformed using the transformer that is passed to the ```each``` method.
```php
$presentation = Present::collection($usersList)->with(function (User $user) {
    return [
        'id' => $user->id,
        'first_name' => ucfirst($user->first_name),
        // ...
    ];
});
``` 

The ```with``` function excepts a ```Transformer``` and returns a ```Presentation``` object. To get the presentation data as array use the ```show``` method.

```php
$array = $presentation->show();
```

Commonly usage of the ```Presentation``` is with an http response object. Usage example with Symphony ```JsonResponse```
```php
$response = new JsonResponse($presentation->show());

$response->send();
```

Hide keys using the ```Hidden``` object.

```php
$presentation = Present::item($user)->with(function (User $user) {
    return [
        'id' => $user->id,
        'first_name' => ucfirst($user->first_name),
        'birthday' => $user->isBirthDayPublic() ? $user->birthday : Present::hidden() // or use Hidden::key()
        // ...
    ];
});
```
When a value in an array data structure is a ```Hidden``` type the key will not be presented. The above example presentation will be

```php
$array = $presentation->show();

// if birthday is not public ->
// $array = [
//     'id' => $user->id,
//     'first_name' => ucfirst($user->first_name),
// ]
// birthday key is not visible.

```

 
The ```with``` method accepts a transformer. A transformer can be a ```callable```, ```Transformer``` instance or a transformer class ```string``` name.

Using a ```Transformer``` class name ```string```
```php
class UserTransformer 
{
    public function transform(User $user) 
    {
        return [
            'id' => $user->id,
            'first_name' => ucfirst($user->first_name),
            // ...
        ];
    } 
}

$presentation = Present::each($usersList)->with(UserTransformer::class);
```

Or with ```Transformer``` instance

```php
$tranformer = new UserTransformer();

$presentation = Present::item($user)->with($transformer);
$presentation = Present::each($usersList)->with($transformer);
```

Nested presentation using a ```Transformer``` class

```php
use Presentit\Present;

class UserTransformer 
{
    public function transform(User $user) 
    {
        return [
            'id' => $user->id,
            'first_name' => ucfirst($user->first_name),
            'friends' => $user->friends ? Present::each($user->friends)->with(UserTransformer::class) : [],
            // ...
        ];
    } 
}

$presentation = Present::each($usersList)->with(UserTransformer::class);
```