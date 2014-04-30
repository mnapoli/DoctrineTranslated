<?php

namespace Test\Mnapoli\Translated\Fixture;

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
     * @var TranslatedString
     * @Embedded(class = "Test\Mnapoli\Translated\Fixture\TranslatedString")
     */
    protected $name;

    public function __construct()
    {
        $this->name = new TranslatedString();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return TranslatedString
     */
    public function getName()
    {
        return $this->name;
    }
}
