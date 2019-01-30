<?php

use PHPUnit\Framework\TestCase;
use MK\HAL\HALObject;
use MK\HAL\HALLink;
use MK\HAL\HALCurie;
use MK\HAL\HALMode;
use MK\HAL\JSONFactory;

final class HALReturnTypeTest extends TestCase {
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

    /**
     * @dataProvider internalTypeProvider
     */
    public function testInternalTypes($expected, $data) {
        $this->assertInternalType($expected, $data);
    }

    /**
     * @dataProvider internalInstanceTypeProvider
     */
    public function testInstanceTypes($expected, $data) {
        $this->assertInstanceOf($expected, $data);
    }

    /**
     * @dataProvider internalInstanceContainsTypeProvider
     */
    public function testInstanceContainsTypes($expected, $data) {
        $this->assertContainsOnlyInstancesOf($expected, $data);
    }

    public function internalTypeProvider() {
        $this->setUp();
        $hal = $this->hal;
        return array(
            array('string', $hal->getSelf()),
            array('array', $hal->getLinks()),
            array('array', $hal->getAllEmbedded()),
            array('string', $hal->export()),
            array('array', $hal->jsonSerialize()),
            array('string', json_encode($hal->jsonSerialize())),
            array('array', JSONFactory::serialize($hal)),
            array('array', $hal->getData())
        );
    }

    public function internalInstanceTypeProvider() {
        $this->setUp();
        $hal = $this->hal;
        return array(
            array(HALLink::class, $hal->getLink("search")),
            array(HALObject::class, $hal->getEmbedded('product'))
        );
    }

    public function internalInstanceContainsTypeProvider() {
        $this->setUp();
        $hal = $this->hal;
        return array(
            array(HALCurie::class, $hal->getCuries()),
            array(HALLink::class, $hal->getLink("admin")),
            array(HALObject::class, $hal->getEmbedded('coupon'))
        );
    }
}