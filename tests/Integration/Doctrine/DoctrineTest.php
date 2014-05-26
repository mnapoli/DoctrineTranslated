<?php

namespace Test\Mnapoli\Translated\Integration\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Test\Mnapoli\Translated\Fixture\MyEntity;

/**
 * @coversNothing
 */
class DoctrineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        $paths = [__DIR__ . '/../../Fixture'];
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        // Create the entity manager
        $config = Setup::createAnnotationMetadataConfiguration($paths, true);
        $config->addCustomStringFunction('TR', 'Mnapoli\Translated\Integration\Doctrine\TranslatedFunction');
        $this->em = EntityManager::create($dbParams, $config);

        // Create the DB
        $tool = new SchemaTool($this->em);
        $tool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    public function testPersist()
    {
        $entity1 = new MyEntity();
        $entity1->getName()->set('foo', 'en');
        $entity1->getName()->set('fou', 'fr');

        $this->em->persist($entity1);
        $this->em->flush();
        $this->em->clear();

        /** @var MyEntity $entity2 */
        $entity2 = $this->em->find(get_class($entity1), $entity1->getId());

        $this->assertEquals($entity1->getName(), $entity2->getName());
    }

    public function testWhere()
    {
        $entity1 = new MyEntity();
        $entity1->getName()->set('hello', 'en');
        $entity1->getName()->set('bonjour', 'fr');
        $this->em->persist($entity1);
        $this->em->flush();

        $results = $this->em->createQuery(
            'SELECT e FROM Test\Mnapoli\Translated\Fixture\MyEntity e WHERE TR(e.name) = \'hello\''
        )->getResult();

        $this->assertCount(1, $results);
        $this->assertSame($entity1, current($results));

        $results = $this->em->createQuery(
            'SELECT e FROM Test\Mnapoli\Translated\Fixture\MyEntity e WHERE TR(e.name) = \'bonjour\''
        )->getResult();

        $this->assertEmpty($results);
    }
}
