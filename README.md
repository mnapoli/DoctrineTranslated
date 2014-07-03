# Translated strings for Doctrine

[![Build Status](https://travis-ci.org/mnapoli/DoctrineTranslated.svg?branch=master)](https://travis-ci.org/mnapoli/DoctrineTranslated)
[![Coverage Status](https://coveralls.io/repos/mnapoli/DoctrineTranslated/badge.png)](https://coveralls.io/r/mnapoli/DoctrineTranslated)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mnapoli/DoctrineTranslated/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mnapoli/DoctrineTranslated/?branch=master)
[![Total Downloads](https://poser.pugx.org/mnapoli/doctrine-translated/downloads.svg)](https://packagist.org/packages/mnapoli/doctrine-translated)

This library is an alternative to the Translatable extension for Doctrine.

The basic idea is to shift from "something that magically manages several versions of the same entity"
to "my entity has a field which has several translations".

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

The `TranslatedString` is defined by you by extending `Mnapoli\Translated\AbstractTranslatedString`.
That way, you can define the languages you want to support.
This class is reusable everywhere in your application, so you only need to define it once.

```php
namespace Acme\Model;

/**
 * @Embeddable
 */
class TranslatedString extends \Mnapoli\Translated\AbstractTranslatedString
{
    /**
     * @Column(type = "string", nullable=true)
     */
    public $en;

    /**
     * @Column(type = "string", nullable=true)
     */
    public $fr;
}
```

As you can see, the properties must be public.

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

$product->getName()->en = 'Some english here';
$product->getName()->fr = 'Un peu de français là';

echo $product->getName()->en;
```

Usually in your application, you will not want to hardcode "en" or "fr" when reading or setting the value.
This is because the current locale varies from request to request.

That is why this library provides helpers to make it much easier, along with the `Translator` object.

Example:

```php
// The default locale is "en" (you can provide a locale like "en_US" too, it will be parsed)
$translator = \Mnapoli\Translated\Translator('en');

// If a user is logged in, we can set the locale to the user's one
$translator->setLanguage('fr');

$str = new TranslatedString();
$str->en = 'foo';
$str->fr = 'bar';

// No need to manipulate the locale here
echo $translator->get($str); // foo
```

Current integrations:

- Twig

```twig
{{ product.name|translate }}
```

The configuration step is very straightforward:

```php
$extension = new \Mnapoli\Translated\Integration\Twig\TranslatedTwigExtension($translator);
$twig->addExtension($extension);
```

- Symfony 2

The `Mnapoli\Translated\Integration\Symfony2\TranslatedBundle` is provided.
You need to register the bundle in `AppKernel.php`:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new \Mnapoli\Translated\Integration\Symfony2\TranslatedBundle(),
        ];

        // ...
```

Then in your `app/config/config.yml`:

```yaml
translated:
    default_locale: %locale%
```

The TranslatedBundle will automatically listen to the request's locale and configure the `Translator` accordingly.

That means you have nothing to do: just use the Translator, and it will use the request's locale to translate things.

If the current locale is not stored inside the request, you will need to set up an event listener manually.
Here is an basic example using the session:

```php
class LocaleListener
{
    private $translator;
    private $session;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $locale = $request->getSession()->get('_locale');
        if ($locale) {
            $this->translator->setLanguage($locale);
        }
    }

    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $lang = $user->getLanguage();

        if ($lang) {
            $this->session->set('_locale', $lang);
        }
    }
}
```

When the user logs in, his/her locale is stored inside the session. Here is the configuration:

```yaml
services:
    acme.locale.interactive_login_listener:
        class: Acme\UserBundle\EventListener\LocaleListener
        calls:
            - [ setSession, [@session] ]
        tags:
            - { name: kernel.event_listener, event: security.interactive_login, method: onLogin }

    acme.locale.kernel_request_listener:
        class: Acme\UserBundle\EventListener\LocaleListener
        calls:
            - [ setSession, [@session] ]
        tags:
            - { name: kernel.event_listener, event: kernel.request, method: onRequest }
```

- Zend Framework 1

```php
    // In your Bootstrap
    protected function _initViewHelpers()
    {
        $this->bootstrap('View');

        // Create or get $translator (\Mnapoli\Translated\Translator)

        // Create the helper
        $helper = new Mnapoli\Translated\Integration\Zend1\TranslateZend1Helper($translator);

        // The view helper will be accessible through the name "translate"
        $this->getResource('view')->registerHelper($helper, 'translate');
    }
```

You can then use the helper in views:

```php
echo $this->translate($someTranslatedString);
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


## Translator

You saw above a basic example of using the translator.

Here is all you can do with it:

```php
// Get the translation for the current locale
echo $translator->get($str);

// Set the translation for the current locale
$translator->set($str, 'Hello');

// Set the translation for several locales
$translator->setMany($str, [
    'en' => 'Hello',
    'fr' => 'Salut',
]);
```

To create a new translation from scratch:

```php
$str = $translator->set(new TranslatedString(), 'Hello');

// Same as:
$str = new TranslatedString();
$translator->set($str, 'Hello');
```


## Operations

Sometimes you need to concatenate strings in a model, so you can't use the translator
(and you maybe don't want to).

You can do some basic operations on the translated strings.

### Concatenation

```php
$str1 = new TranslatedString();
$str1->en = 'Hello';
$str1->fr = 'Bonjour';

// $result is a TranslatedString
$result = $str1->concat(' ', $user->getName());

// Will echo "Hello John" or "Bonjour John" according to the locale
echo $translator->get($result);
```

You can also create a string concatenation from scratch:

```php
$result = TranslatedString::join([
    new TranslatedString('Hello', 'en'),
    '!'
]);
```

### Implode

Just like the concatenation:

```php
$result = TranslatedString::implode(', ', [
    new TranslatedString('foo', 'en'),
    'bar'
]);

// "foo, bar"
echo $result->en;
```


## Untranslated strings

Sometimes you should give or return a `TranslatedString` but you have a non-translated string.
For example:

```php
public function getParentLabel() {
    if ($this->parent === null) {
        return '-';
    }

    return $this->parent->getLabel();
}
```

Here there is a problem: `'-'` is a simple string, and if the calling code expects a `TranslatedString`
then it won't work.

For this, you can simply create an "untranslated" string:

```php
return TranslatedString::untranslated('-');
```

It will have the same value (or translation) for every language.


## Fallbacks

You can define fallbacks on the `Translator`:

```php
$translator = new Translator('en', [
    'fr' => ['en'],       // french fallbacks to english if not found
    'es' => ['fr', 'en'], // spanish fallbacks to french, then english if not found
]);
```

As you can see, fallbacks are optional, and can be multiple.

Now the translator will use those fallbacks:

```php
$str = new TranslatedString();
$str->en = 'Hello!';

// Will show nothing (no FR value)
echo $str->fr;

$translator->setLanguage('fr');
// Will show "Hello!" because the french falls back to english if not defined
echo $translator->get($str);
```

You will note that you can also directly use fallbacks on the TranslatedString object:

```php
// Nothing
echo $str->fr;
// Nothing
echo $str->get('fr');
// Will show "Hello!" (the fallback is "en")
echo $str->get('fr', ['en']);
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

The `$lang` (current locale) can be obtained from the `Translator`.

I am looking at ways to makes this more simple, for example with a DQL function
(https://github.com/mnapoli/DoctrineTranslated/blob/master/src/Doctrine/TranslatedFunction.php).
Feel free to help, currently this is stuck because Doctrine instantiate the "function" classes itself,
which prevents using dependency injection to inject the current locale (or translator).

Current issue opened at Doctrine: [#991](https://github.com/doctrine/doctrine2/pull/991).
