<?php

namespace Ais\ProdiBundle\Tests\Handler;

use Ais\ProdiBundle\Handler\ProdiHandler;
use Ais\ProdiBundle\Model\ProdiInterface;
use Ais\ProdiBundle\Entity\Prodi;

class ProdiHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\ProdiBundle\Tests\Handler\DummyProdi';

    /** @var ProdiHandler */
    protected $prodiHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $prodi = $this->getProdi();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($prodi));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->prodiHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $prodis = $this->getProdis(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($prodis));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->prodiHandler->all($limit, $offset);

        $this->assertEquals($prodis, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $prodi = $this->getProdi();
        $prodi->setTitle($title);
        $prodi->setBody($body);

        $form = $this->getMock('Ais\ProdiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($prodi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $prodiObject = $this->prodiHandler->post($parameters);

        $this->assertEquals($prodiObject, $prodi);
    }

    /**
     * @expectedException Ais\ProdiBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $prodi = $this->getProdi();
        $prodi->setTitle($title);
        $prodi->setBody($body);

        $form = $this->getMock('Ais\ProdiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->prodiHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $prodi = $this->getProdi();
        $prodi->setTitle($title);
        $prodi->setBody($body);

        $form = $this->getMock('Ais\ProdiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($prodi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $prodiObject = $this->prodiHandler->put($prodi, $parameters);

        $this->assertEquals($prodiObject, $prodi);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $prodi = $this->getProdi();
        $prodi->setTitle($title);
        $prodi->setBody($body);

        $form = $this->getMock('Ais\ProdiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($prodi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->prodiHandler = $this->createProdiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $prodiObject = $this->prodiHandler->patch($prodi, $parameters);

        $this->assertEquals($prodiObject, $prodi);
    }


    protected function createProdiHandler($objectManager, $prodiClass, $formFactory)
    {
        return new ProdiHandler($objectManager, $prodiClass, $formFactory);
    }

    protected function getProdi()
    {
        $prodiClass = static::DOSEN_CLASS;

        return new $prodiClass();
    }

    protected function getProdis($maxProdis = 5)
    {
        $prodis = array();
        for($i = 0; $i < $maxProdis; $i++) {
            $prodis[] = $this->getProdi();
        }

        return $prodis;
    }
}

class DummyProdi extends Prodi
{
}
