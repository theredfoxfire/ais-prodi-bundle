<?php

namespace Ais\ProdiBundle\Tests\Fixtures\Entity;

use Ais\ProdiBundle\Entity\Prodi;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadProdiData implements FixtureInterface
{
    static public $prodis = array();

    public function load(ObjectManager $manager)
    {
        $prodi = new Prodi();
        $prodi->setTitle('title');
        $prodi->setBody('body');

        $manager->persist($prodi);
        $manager->flush();

        self::$prodis[] = $prodi;
    }
}
