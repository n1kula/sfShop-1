<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;

class WebTestCase extends BaseWebTestCase
{
    protected function setUp()
    {
        static::$kernel = static::createKernel();
		static::$kernel->boot();
		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager();
        
        // reset db
		$purger = new ORMPurger($this->em);
		$purger->purge();
        
        $loader = new Loader();
		$loader->addFixture(new \AppBundle\DataFixtures\ORM\LoadUserData());
        $loader->addFixture(new \AppBundle\DataFixtures\ORM\LoadCategoryData());
        $loader->addFixture(new \AppBundle\DataFixtures\ORM\LoadProductsData());
		
		$purger = new ORMPurger($this->em);
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures());
    }
}