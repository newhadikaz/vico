<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\Project;
use App\Entity\Vico;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       
        $member = new Member();

        $member->setUsername('hadi');
        $member->setPassword(password_hash("12345678", PASSWORD_BCRYPT));
        $member->setCreated(Carbon::now());
        $member->setFirstName("hadi");
        $member->setLastName("kaz");    
        $manager->persist($member);

        $vico = new Vico();
        $vico->setName('vico');
        $vico->setCreated(Carbon::now());
        $manager->persist($vico);

        $project = new Project();

        $project->setVico($vico);
        $project->setMember($member);
        $project->setTitle('test project');
        $project->setCreated(Carbon::now());
        $manager->persist($project);


        $manager->flush();
    }
}
