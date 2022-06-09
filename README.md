Initial Release


# Random Numbers in PHP

## What is a Random Number?
Random number does NOT mean
a different number every time.
Random means something that
can not be predicted logically.

Random numbers generated through a generation algorithm are called pseudo random.

## Generate Random Number With simple-PHP-Random 
Simple-PHP-Random offers some methods to work with random numbers.
All methods of this class are inspired by
 numpy (python) random module but they all take $size (shape) as last argument( except methods like shuffle).
## Simple-Php-Random methods
Here are available methods in this class:
$size is the shape of return array( default is null). Its value must be int|array of int|verdiac (e.g: $size=5 | $size=[5,2] | 5,3,4 )  
 -random()
 -rand(?int|array|verdiac $size)
 -randn(?$size)
 -normal(?float $min=0.0, ?float $max=1.0, ?$size )
 -randint(int $start, ?$end, ?$size)
 -randrange(int $start, ?int $end, ?$size)
 -choice(array $values, ?array $p, ?$size) 
 -shuffle(array $array)
 -sample()
 -seed(int $seed)

Example
Generate a random integer from 0
to 100:
```php
include "Random.php";
use Jehsh\Random;

$x = Random::randint(100)
print_r($x);  

```
Generate Random Float
The random module's
 rand() method returns a random float between 0 and 1.
Example
Generate a random float from 0 to 1:

 ```php
 include 'Random.php'; 
 use Jehsh\Random; 
$x = Random::rand()
print_r($x);  
```

Generate Random Array
In NumPy we work with arrays,
and you can use the two
methods from the above
examples to make random arrays.
Integers
The randint() method takes a

size parameter where you can specify the shape of an array.
Example
Generate a 1-D array containing 5
random integers from 0 to 100:
 ```php
 include 'Random.php'; use Jehsh\Random; 
$x=Random::randint(100,null,5)//5 is size, 100 is end and 0 is start
print_r($x); 
 ```


Generate a 2-D array with 3 rows,
each row containing 5 random
integers from 0 to 100:
 ```php 
include 'Random.php';
 use Jehsh\Random; 
$x = Random::randint(100,null,3,5)//or Random::randint(100,null,[3,5])
print_r($x);  
```


Floats
The rand() method also allows you to specify the shape of the
array.
Example
Generate a 1-D array containing 5 random floats:
 ```php
 include 'Random.php';
 use Jehsh\Random; 
$x = Random::rand(5)
print_r($x);  
```


Generate a 2-D array with 3 rows,
each row containing 5 random
numbers:
 ```php 
include 'Random.php';
use Jehsh\Random; 
$x = Random::rand(3, 5)
print_r($x);  
```


Generate Random Number From Array 
The choice() method allows you to generate a random value based on an array of values.
The  choice() method takes an array as a parameter and
randomly returns one of the values.

Example
Return one of the values in an
array:
 ```php
 include 'Random.php';
 use Jehsh\Random; 
$x = Random::choice([3, 5, 7, 9])
print_r($x);  
```


The choice() method also allows
you to return an array of values.
Add a size parameter to specify the shape of the array.
Example
Generate a 2-D array that
consists of the values in the
array parameter (3, 5, 7, and 9):
 ```php 
include 'Random.php'; 
use Jehsh\Random; 
$x = Random::choice([3, 5, 7, 9],null,3, 5)
print_r($x);  
```
It Generates new values or array of  values from array depending on their probabilities( or weights).
Let's say: you have this array: array(4,1,7) and you want to give 4 50% of chance to appear, 1 10% and 7 40%.
```php
include 'Random.php';
use Jehsh\Random;

$values = [4,1,7];
$p = [50,10,40];
$return_values = Random::choice($values,$p,1000);//we want 1000 values
rsort($return_values);
print_r($return_values);

```

##bContribution 
If you have something to help to improve this simple class, feel free to contribute.

## Recommandation#m
If you'd like to find a numpy alternative in php, there is a lib, numphp [numphp website](https://sciphp.org) [numphp github](https://github.com/sciphp/numphp), it miss a Random number class but you can use it with this Simple PHP Random or add it yourself.
Here Is  [NumPhp with Random class](https://github.com/pmulwahali/numphp) (see 0.4.5 )

## Licence
MIT 
