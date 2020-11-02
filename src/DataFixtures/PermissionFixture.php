<?php

namespace App\DataFixtures;

use App\Entity\Cluster;
use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PermissionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $user_cluster = new Cluster();
        $user_cluster->setName('User');
        $user_cluster->setDescription('The regular user cluster');
        $manager->persist($user_cluster);

        $admin_cluster = new Cluster();
        $admin_cluster->setName('Admin');
        $admin_cluster->setDescription('The admin cluster');
        $manager->persist($admin_cluster);

        $permission = new Permission();

        $permission->setName('view_dashboard');
        $permission->addCluster($user_cluster);
        $permission->setDescription('Can view dashboard');

        $manager->persist($permission);

        $permission = new Permission();

        $permission->setName('view_user_list');
        $permission->addCluster($admin_cluster);
        $permission->setDescription('Can view list of all users');

        $manager->persist($permission);

        $manager->flush();
    }
}
