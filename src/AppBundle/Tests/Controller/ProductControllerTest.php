<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\WebTestCase;

class ProductControllerTest extends WebTestCase
{
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
        
        $link = $crawler
            // wybieramy link "Wyczyść koszyk"
            ->selectLink('Wyczyść Koszyk')
            ->link()
        ;
        
        // klikamy w link "Wyczyść koszyk"
        $crawler = $client->click($link);
        // przekierowanie po wyczyszczeniu koszyka
        $crawler = $client->followRedirect();
        
        // jedna h1: Koszyk
        $this->assertEquals(1, $crawler->filter('h1:contains("Koszyk")')->count());
        // koszyk powinien być pusty
        $this->assertEquals(0, $crawler->filter('table.table tbody>tr')->count());
    }
    
}