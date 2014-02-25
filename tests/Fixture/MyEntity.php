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
     * @var MyTranslatedString
     * @Embedded(class = "Test\Mnapoli\Translated\Fixture\MyTranslatedString")
     */
    protected $name;

    public function __construct()
    {
        $this->name = new MyTranslatedString();
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
}
