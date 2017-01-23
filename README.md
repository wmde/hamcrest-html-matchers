This is the set of Hamcrest matchers for HTML assertrions 
=========================================================

Usage examples
--------------
Hamcrest allows to create pretty complicated and flexible assertions. Just remember:

*"You can" does not mean "you should".*


The following example shows how we can ensure that there is an HTML form and password entered in it is not weak: 

```php
$html = '<div>
            <form>
                <input name="password" value="strong password"/>
            </form>
         </div>';

assertThat($html, is(htmlPiece(
    havingChild(
        both(withTagName('form'))
        ->andAlso(
            havingDirectChild(
                allOf(
                    withTagName('input'),
                    withAttribute('name')->havingValue('password'),
                    withAttribute('value')->havingValue(not('weak password')))))))));
```
Usage limitations:
  * Each HTML assertion starts with `htmlPiece()` call (`is()` can be used to improve readability)
  * One of `havingRootElement()`, `havingDirectChild()` or `havingChild()` needed to be passed as an argument to `htmlPiece()`

Documentation
-------------
Information about general Hamcrest usage can be found at [Hamcrest github repository](https://github.com/hamcrest/hamcrest-php).


Available Matchers
------------------
* [havingRootElement](../master/README.md#havingRootElement)
* [havingDirectChild](../master/README.md#havingDirectChild)
* [havingChild](../master/README.md#havingChild)
* [withTagName](../master/README.md#withTagName)
* [withAttribute](../master/README.md#withAttribute)
* [havingTextContents](../master/README.md#havingTextContents)


### havingRootElement

* `havingRootElement` - checks given constraint against root element of HTML
```php
assertThat('<p></p>', htmlPiece(havingRootElement(withTagName('p'))));
```

* `havingDirectChild` - checks given constraint against direct children 
```php
assertThat('<p><b></b></p>', htmlPiece(havingRootElement(havingDirectChild(withTagName('b')))));
```

* `havingChild` - checks given constraint against all children 
```php
assertThat('<p><b></b></p>', htmlPiece(havingChild(withTagName('b'))));
```

* `withTagName` - checks given constraint against tag name
```php
assertThat('<p><b></b></p>', htmlPiece(havingChild(withTagName(
    either(equalTo('i'))->orElse(equalTo('b'))
))));
```

* `withAttribute` - checks given constraint against elements attributes comparing names and values
```php
assertThat('<p><input required></p>', htmlPiece(havingChild(withAttribute('required'))));
assertThat('<p><input data-value="some data"></p>', htmlPiece(havingChild(
    withAttribute(startsWith('data-'))->havingValue('some data'))));
```

* `havingTextContents` - checks given constraint against elements text contents
```php
assertThat('<div><p>this is Some Text</p></div>', htmlPiece(havingChild(
    havingTextContents(containsString('some text')->ignoringCase()))));
```
