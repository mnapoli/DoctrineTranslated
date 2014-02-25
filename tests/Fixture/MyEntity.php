<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\TranslationContext;

/**
 * @Entity
 */
class MyEntity
{
    /**
     * @var int
     * @Id @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @var MyTranslatedString
     * @Embedded(class = "Test\Mnapoli\Translated\Fixture\MyTranslatedString")
     */
    protected $name;

    public function __construct()
    {
        $this->name = new MyTranslatedString(new TranslationContext('en'));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return MyTranslatedString
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string      $name
     * @param string|null $locale
     */
    public function setName($name, $locale = null)
    {
        $this->name->set($name, $locale);
    }
}
