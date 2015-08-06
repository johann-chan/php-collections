# php-collections
Set of PHP collections

This project is currently in progress !

#Set of PHP collections.

It has 4 types of collections

1 **Map** : Regular hashmap represented in json as {"a" => "b", "c" => 3}

2 **Sequence** : only numerical index represented in json as [1, 2, 3]

3 **Set** : Sequence with unique entries. Unlike php native array_unique, it somehow remove objects and array (to a certain extend)

4 **Structure** : Map with defined keys. Every keys must have a corresponding values

Each of these have their Immutable Part, **ImmMap**, **ImmSequence**, **ImmSet**, **ImmStructure**. On The immutable collections, you can NOT add, remove, or edit any entries ! 

#Methods

Each methods return new Collection, so NEVER a collection is altered in anyway. (unlike some PHP functions which pass array by reference)

It also implements **ArrayAccess**, **Iterator**, **Countable**. 

**filter**
```php
  $map = new Map(["a" => 1, "b" => 2, "c" => 3]);
  $filtered = $map->filter(function($_) {return ($_ & 1);});
  echo $filtered->count(); //2
  echo $filtered->get("a"); //1
  echo $filtered->a; //1
  echo $filtered["a"]; //1, these are equivalent way to access data
```

**filterKey**
```php
  $set = new Set([1, 2, 3]);
  $filtered = $set->filterKey(function($_) {return ($_ & 1);});
  echo $filtered->count(); //1
  echo $filtered[0] = 2;
```

**foldLeft**
```php
  $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
  echo $map->foldLeft(function($v, $acc) {return (string) $acc . (string) $v;}); //1233
```

**foldRight**
```php
  $seq = new Sequence([1, 2, 3, 4, 5]);
  echo $seq->foldRight(function($v, $acc) {return $acc === null ? $v : $acc * $v;}); //120
```

**isEmpty**
```php
  $seq = new Sequence([null]);
  echo $seq->isEmpty()); //0
  echo $seq->filter(function($i) {return $i !== null;})->isEmpty(); //1
```

**map**
```php
  $struct = new Structure(["a" => 1, "b" => 2, "c" => 3], ["a", "b", "c"]);
  $highLevelFunction = function($pow) {
      return function($item) use ($pow) {
          return pow($item, $pow);
      };
  };
  $filtered = $struct->map($highLevelFunction(2));
  echo $filtered["a"]; //1
  echo $filtered["b"]; //4
  echo $filtered["c"]; //9
```

**toArray**
```php

```

# LOT MORE COMING SOON !!
