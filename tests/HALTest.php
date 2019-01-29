<?php

use PHPUnit\Framework\TestCase;
use \MK\HAL;
use MK\HAL\HALObject;
use MK\HAL\HALLink;

final class HALTest extends TestCase {
    public function testHALCreation() {
        $hal = new HALObject('/customer');
        $hal->addLink(new HALLink('search', '/customer?search=?'));

        $this->assertInstanceOf(HALObject::class, $hal);
        
        $links = $hal->getLinks();
        $this->assertInternalType('array', $links);
        $this->assertEquals(1, count($links));
        $first = reset($links);
        $this->assertInstanceOf(HALLink::class, $first);
    }


    public function testHALJsonOutput() {
        $hal = new HALObject('/customer');
        $hal->addLink(new HALLink('search', '/customer?search=?'));

        $product = new HALObject('/product/1');
        $product->addLink(new HALLink('next', '/product/2'));
        $hal->embed('product', $product);

        $hal->embedCollection('coupon', array(new HALObject('/coupon/5'), new HALObject('/coupon/6')));

        $this->assertJsonStringEqualsJsonFile(__DIR__.'/TestAssert.json', json_encode($hal));
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/TestAssert.json', $hal->exportJson());
    }
}