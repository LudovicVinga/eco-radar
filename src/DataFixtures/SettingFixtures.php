<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting = $this->createSetting();

        $manager->persist($setting);

        $manager->flush();
    }

    /**
     * Cette méthode permet de créer le super admin.
     */
    private function createSetting(): Setting
    {
        $setting = new Setting();

        return $setting
           ->setEmail('eco-radar@gmail.com')
           ->setPhone('07 43 09 88 76')
           ->setCreatedAt(new \DateTimeImmutable())
           ->setUpdatedAt(new \DateTimeImmutable())
        ;
    }
}
