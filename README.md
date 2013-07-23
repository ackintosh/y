#A intuitive tool for drawing graph.


##Installation
`composer.json`

```
{
    "require": {
        "ackintosh/Y": "dev-master"
    }
}
```

```
$ php composer.phar install
```

#Usage

###y = x

```php
Ackitnosh\Y::equals()->_X()->output();
```

![y=x](https://dl.dropboxusercontent.com/u/22083548/github/Y/x.png)

###y = x + 50

```php
Ackitnosh\Y::equals()->_X()->_plus(50)->output();
```

![y=x+50](https://dl.dropboxusercontent.com/u/22083548/github/Y/x%2B50.png)

###y = x^2

```php
Ackitnosh\Y::equals()->_X_squared()->output();
```

![y=x^2](https://dl.dropboxusercontent.com/u/22083548/github/Y/x%5E2.png)

###y = 5x^2 - 10

```php
Ackitnosh\Y::equals()->_5X_squared()->minus(10)->output();
```

![y=5x^2-100](https://dl.dropboxusercontent.com/u/22083548/github/Y/5x-100.png)


#Requirements
- PHP 5.3 or greater
- GD