<?php

namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\Title;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TitleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jstor = new Title();
        $jstor->setTitle('Jstor');
        $jstor->setLastModified(new DateTime());
        $jstor_location = new Location();
        $jstor_location->setLocation('http://jstor.org');
        $jstor->addLocation($jstor_location);

        $manager->persist($jstor_location);
        $manager->persist($jstor);

        $manager->flush();
    }
}
