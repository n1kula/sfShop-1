<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;

class ProductControllerTest extends WebTestCase
{
    protected static $application;

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

    
    public function testAddToCart()
    {
        $client = static::createClient();

        // wchodzimy na stronę główną
        $crawler = $client->request('GET', '/');

        $link = $crawler
            // wszystkie linki "Pokaż"
            ->filter('a:contains("Pokaż")')
            // wybieramy drugi link
            ->eq(1) 
            // wybieramy jako link
            ->link()
        ;

        // klikamy w link
        $crawler = $client->click($link);
            
        $link = $crawler
            // wybieramy link
            ->selectLink('Dodaj do koszyka')
            ->link()
        ;
        
        // klikamy w link "Dodaj do koszyka"
        $crawler = $client->click($link);
        // przekierowanie po pomyślnym dodaniu do koszyka
        $crawler = $client->followRedirect();
        
        // jedna h1: Koszyk
        $this->assertEquals(1, $crawler->filter('h1:contains("Koszyk")')->count());
        // 1 element w koszyku
        $this->assertEquals(1, $crawler->filter('table.table tbody>tr')->count());
    }
    
}