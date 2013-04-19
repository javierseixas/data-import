<?php

namespace Ddeboer\DataImport\Tests\Writer;

use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Tests\Fixtures\TestEntity;

class DoctrineWriterTest extends \PHPUnit_Framework_TestCase
{

    protected $em;
    protected $repo;
    protected $metadata;

    public function setUp()
    {
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(array('getRepository', 'getClassMetadata', 'persist'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->repo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->setMethods(array('getName', 'getFieldNames', 'setFieldValue'))
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testWriteItem()
    {


        $this->metadata->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('Ddeboer\DataImport\Tests\Fixtures\TestEntity'));

        $this->metadata->expects($this->any())
                ->method('getFieldNames')
                ->will($this->returnValue(array('firstProperty', 'secondProperty')));

        $this->em->expects($this->once())
                ->method('getRepository')
                ->will($this->returnValue($this->repo));

        $this->em->expects($this->once())
                ->method('getClassMetadata')
                ->will($this->returnValue($this->metadata));

        $this->em->expects($this->once())
                ->method('persist');

        $writer = new DoctrineWriter($this->em, 'DdeboerDataImport:TestEntity');

        $item = array(
            'firstProperty' => 'some value',
            'secondProperty'=> 'some other value'
        );

        $writer->writeItem($item);
    }

    public function testWriteItemWithAssociatedEntities()
    {

    }
}
