# Translated strings for Doctrine

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

The `TranslatedString` is defined by you by extending `Mnapoli\Translated\TranslatedString`.
That way, you can define the languages you want to support.
This class is reusable everywhere in your application, so you only need to define it once.

```php
namespace Acme\Model;

/**
 * @Embeddable
 */
class TranslatedString extends \Mnapoli\Translated\TranslatedString
{
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

You can then starting translating that field:

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
// The translation context sets that the current locale is EN
$helper = new TranslationHelper(new TranslationContext('en'));

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

## Fallbacks

TODO
