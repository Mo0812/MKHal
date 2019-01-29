<?php

use PHPUnit\Framework\TestCase;
use MK\HAL\HALObject;
use MK\HAL\HALLink;
use MK\HAL\HALCurie;
use MK\HAL\HALMode;

final class HALFunctionalTest extends TestCase {
    /**
     * Root HAL object
     *
     * @var HALObject
     */
    private $hal;

    public function setUp() {
        $this->hal = new HALObject('/customer');

        $this->hal->addCurie(new HALCurie('ea', 'http://example.com/docs/rels/{rel}'));

        $this->hal->addLink('search', new HALLink('/customer?search=?'));

        $this->hal->addLinkCollection('admin', array(
            new HALLink('/admin/2'),
            new HALLink('/admin/7')
        ));

        $product = new HALObject('/product/1');
        $product->addLink('next', new HALLink('/product/2'));
        $this->hal->embed('product', $product);

        $this->hal->embedCollection('coupon', array(new HALObject('/coupon/5'), new HALObject('/coupon/6')));

    }

    public function testHALTypes() {
        $hal = $this->hal;
        $this->assertInstanceOf(HALObject::class, $hal);
        
        // Curies
        $curies = $hal->getCuries();
        $this->assertInternalType('array', $curies);
        $this->assertEquals(1, count($curies));
        $first = reset($curies);
        $this->assertInstanceOf(HALCurie::class, $first);

        // Links
        $links = $hal->getLinks();
        $this->assertInternalType('array', $links);
        $this->assertEquals(2, count($links));
        $first = reset($links);
        $this->assertInstanceOf(HALLink::class, $first);

        // Embedded
        $embedded = $hal->getAllEmbedded();
        $this->assertInternalType('array', $embedded);
        $this->assertEquals(2, count($embedded));
        $first = reset($embedded);
        $this->assertInstanceOf(HALObject::class, $first);
    }

    public function testHALLinkChaining() {
        $hal = $this->hal;
        $hal->addLink('chaining', new HALLink('/chaining/1'));

        $chaining = $hal->getLink('chaining');
        $this->assertInstanceOf(HALLink::class, $chaining);

        $hal->addLinkCollection('chaining', array(
            new HALLink('/chaining/2'),
            new HALLink('/chaining/3')
        ));

        $chaining = $hal->getLink('chaining');
        $this->assertInternalType('array', $chaining);
        $this->assertEquals(3, count($chaining));
        $this->assertInstanceOf(HALLink::class, $chaining[1]);
    }

    public function testHALEmbeddedChaining() {
        $hal = $this->hal;
        $hal->embed('chaining', new HALObject('/chaining/1'));

        $chaining = $hal->getEmbedded('chaining');
        $this->assertInstanceOf(HALObject::class, $chaining);

        $hal->addLinkCollection('chaining', array(
            new HALObject('/chaining/2'),
            new HALObject('/chaining/3')
        ));

        $chaining = $hal->getLink('chaining');
        $this->assertInternalType('array', $chaining);
        $this->assertEquals(2, count($chaining));
        $this->assertInstanceOf(HALObject::class, $chaining[1]);
    }

    public function testHALAttributeOverwrite() {
        $hal = $this->hal;
        $hal->addLink('search', new HALLink('/customer?find=?'));
        $link = $hal->getLink('search');
        
        $this->assertInternalType('array', $link);
        $this->assertEquals(2, count($link));

        $hal->addLink('search', new HALLink('/customer?lookup=?'), HALMode::OVERWRITE);
        $link = $hal->getLink('search');
        
        $this->assertInstanceOf(HALLink::class, $link);
    }

    public function testHALJsonOutput() {
        $hal = $this->hal;

        $this->assertJsonStringEqualsJsonFile(__DIR__.'/assets/TestAsset.json', json_encode($hal));
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/assets/TestAsset.json', $hal->export());
    }
}