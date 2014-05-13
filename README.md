# Translated strings for Doctrine

[![Build Status](https://travis-ci.org/mnapoli/DoctrineTranslated.svg?branch=master)](https://travis-ci.org/mnapoli/DoctrineTranslated) [![Coverage Status](https://coveralls.io/repos/mnapoli/DoctrineTranslated/badge.png)](https://coveralls.io/r/mnapoli/DoctrineTranslated)

This library is an alternative to the Translatable extension for Doctrine.

The basic idea is to shift from "something that magically manages several versions of the same entity"
to "my entity has a field which has several values".

It aims to be extremely simple and explicit on its behavior, so that it can be
reliable, maintainable, easily extended and understood. The goal is to do more with less.

## Requirements

This library requires **PHP 5.4** and **Doctrine 2.5**!

## How it works

The library relies on a new major feature of Doctrine 2.5: embedded objects.
An embedded object will see its properties inserted in the entity that uses it.

Example:

```php
namespace Acme\Model;

/**
 * @Entity
 */
class Product
{
    /**
     * @var TranslatedString
     * @Embedded(class = "Acme\Model\TranslatedString")
     */
    protected $name;

    public function __construct()
    {
        $this->name = new TranslatedString();
    }

    public function getName()
    {
        return $this->name;
    }
}
```

If you use YAML instead of annotations:

```yaml
Acme\Model\Product:
  type: entity

  embedded:
    name:
      class: Acme\Model\TranslatedString
```

The `TranslatedString` is defined by you by implementing `Mnapoli\Translated\TranslatedStringInterface`.
That way, you can define the languages you want to support.
This class is reusable everywhere in your application, so you only need to define it once.

```php
namespace Acme\Model;

/**
 * @Embeddable
 */
class TranslatedString implements \Mnapoli\Translated\TranslatedStringInterface
{
    use \Mnapoli\Translated\TranslatedStringTrait;

    /**
     * @Column(type = "string", nullable=true)
     */
    protected $en;

    /**
     * @Column(type = "string", nullable=true)
     */
    protected $fr;
}
```

Don't forget to use the trait `Mnapoli\Translated\TranslatedStringTrait` in the class.

Here is the same mapping in YAML:

```yaml
Acme\Model\TranslatedString:
  type: embeddable
  fields:
    en:
      type: string
      nullable: true
    fr:
      type: string
      nullable: true
```

You can then start translating that field:

```php
$product = new Product();

$product->getName()->set('Some english here', 'en');
$product->getName()->set('Un peu de français là', 'fr');

echo $product->getName()->get('en');
```

It looks clumsy right? Well there's more of course.

Usually in your application, you will not want to hardcode "en" or "fr" when reading or setting the value.
This is because the current locale varies from request to request.

That is why this library aims to provide helpers to make it much easier.
A `TranslationHelper` is provided, and integrations with Twig or other systems are planned.

Example:

```php
// The default locale is "en"
$translationManager = TranslationManager('en');

// If a user is logged in, we can set the locale to the user's one
$translationManager->setCurrentContext('fr');

$helper = new TranslationHelper($translationManager);

$str = new TranslatedString();
$str->set('foo', 'en');
$str->set('bar', 'fr');

// No need to manipulate the locale here
echo $helper->toString($str); // foo
```

Twig example (not implemented yet):

```twig
{{ product.name|translated }}
```

Current integrations:

- Zend Framework 1

```php
// In your Bootstrap
    protected function _initViewHelpers()
    {
        $this->bootstrap('View');
        $view = $this->getResource('view');

        // Create or get $translationHelper (\Mnapoli\Translated\TranslationHelper)

        $viewHelper = new \Mnapoli\Translated\Helper\Zend1\TranslateZend1Helper($translationHelper);

        // The view helper will be accessible through the name "translate"
        $view->registerHelper($viewHelper, 'translate');
    }
```

You can then use the helper in views:

```php
echo $this->translate($aTranslatedString);
```

Watch out: the `translate` view helper already exists in ZF1. The example shown here will override it.
You can use a name different than "translate" if you don't want to override it.


## Pros and cons

With that method, you will end up with only one table in database:

```
mysql> SELECT * FROM Product;
+----+---------+---------+
| id | name_en | name_fr |
+----+---------+---------+
| 1  | Hello   | Salut   |
+----+---------+---------+
```

This makes it very good for performances, and for other reasons:

- no round-trip to the database because you always get all the translations
- no joins, this is a perfectly simple query
- isolated translations (there isn't a single table for storing all the translations)
- no problems with indexes (you can add the indexes you want)
- very friendly with manually browsing/editing the database

However, be aware there are cons:

- if you support 100 languages, you will end up with huge tables and large objects in memory
- if you add a new language, you need to update your database (Doctrine can do it automatically though)


## Translation context

Everything in this library works around the `TranslationContext`.
It is really simple: **it just contains the current locale**.

For example, if you handle a HTTP request with a 'fr_FR' locale, then
you will create a translation context with that locale.

You can then use this context to create the helpers.

You can create a new context (with a default locale):

```php
$manager = new TranslationManager('en');
```

and set the current user's locale:

```php
$context = $manager->setCurrentContext('fr');
```

A good place to do this would be at the beginning of a HTTP request, so that the current
context is set for the whole request (and available in controllers).

Later, you can fetch the current context:

```php
$context = $manager->getCurrentContext();
```


## Helper

You saw above a basic example of using the helper.

Here is all you can do with it:

```php
// Get the translation for the current locale
echo $helper->toString($str);

// Set the translation for the current locale
$helper->set($str, 'Hello');

// Set the translation for several locales
$helper->setMany($str, [
    'en' => 'Hello',
    'fr' => 'Salut',
]);
```

To create a new translation from scratch:

```php
$str = $helper->set(new TranslatedString(), 'Hello');

// Same as:
$str = new TranslatedString();
$str = $helper->set($str, 'Hello');
```


## Operations

Sometimes you need to concatenate strings in a model, so you can't use the helper
(and you maybe don't want to).

You can do some basic operations on the translated strings.

### Concatenation

```php
$str1 = new TranslatedString();
$str1->set('Hello', 'en');
$str1->set('Bonjour', 'fr');

// $result is an instance of TranslatedStringInterface
$result = $str1->concat(' ', $user->getName());

// Will echo "Hello John" or "Bonjour John" according to the locale
echo $helper->toString($result);
```

You can also create a string concatenation from scratch:

```php
$result = new StringConcatenation(new TranslatedString('Hello', 'en'), '!');

// or
$result = StringConcatenation::fromArray([
    new TranslatedString('Hello', 'en'),
    '!'
]);
```

### Implode

Just like the concatenation:

```php
$result = StringConcatenation::implode(' ', [
    new TranslatedString('foo', 'en'),
    'bar'
]);

// "foo bar"
echo $helper->toString($result);
```


## Untranslated strings

Sometimes you should give or return a `TranslatedStringInterface` but you have a non-translated string.
For example:

```php
public function getParentLabel() {
    if ($this->parent === null) {
        return '-';
    }

    return $this->parent->getLabel();
}
```

Here there is a problem: `'-'` is a simple string, and if the calling code expects a `TranslatedStringInterface`
then it won't work.

For this, you can use the `UntranslatedString` class:

```php
return new UntranslatedString('-');
```


## Fallbacks

You can define fallbacks on the `TranslationManager`:

```php
$manager = new TranslationManager('en', [
    'fr' => ['en'],       // french fallbacks to english if not found
    'es' => ['fr', 'en'], // spanish fallbacks to french, then english if not found
]);
```

As you can see, fallbacks are optional, and can be multiple.

Once fallbacks are configured, they will be embedded in the `TranslationContext`:

```php
$context = $manager->setCurrentContext('es');

var_dump($context->getFallback()); // [ 'fr', 'en' ]
```


## Doctrine

**Documentation currently being written**

There are no changes regarding persisting or retrieving an entity. When you load an entity from
database, all the translations will be loaded.

However, due to the fact that `Product::name` is not a string anymore, you cannot simply filter on
the field. You need to write queries like this:

```php
$query = $em->createQuery(sprintf(
    "SELECT p FROM Product p WHERE p.name.%s = 'Hello'",
    $lang
));
$products = $query->getResult();
```

The same goes for `ORDER BY`:

```php
$query = $em->createQuery(sprintf(
    "SELECT p FROM Product p ORDER BY p.name.%s ASC",
    $lang
));
$products = $query->getResult();
```

The `$lang` (or locale) can be obtained from the current `TranslationContext`.

I am looking at ways to makes this more simple, for example with a DQL function
(https://github.com/mnapoli/DoctrineTranslated/blob/master/src/Doctrine/TranslatedFunction.php).
Feel free to help, currently this is stuck because Doctrine instantiate the "function" classes itself,
which prevents using dependency injection to inject the current context (containing the current locale).
