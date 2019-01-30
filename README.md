# MKHal

[![Build status](https://travis-ci.org/Mo0812/MKHal.svg?branch=master)](https://travis-ci.org/Mo0812/MKHal.svg?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8d75d9cbe36f42438ce3bcc9d9cbb27d)](https://www.codacy.com/app/Mo0812/MKHal?utm_source=github.com&utm_medium=referral&utm_content=Mo0812/MKHal&utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/mk/hal/v/stable)](https://packagist.org/packages/mk/hal)
[![Latest Unstable Version](https://poser.pugx.org/mk/hal/v/unstable)](https://packagist.org/packages/mk/hal)
[![License](https://img.shields.io/github/license/Mo0812/MKHal.svg)](https://img.shields.io/github/license/Mo0812/MKHal.svg)

> This repository is still in a early development stage. If you find any issues or miss any feature please feel free to raise an issue.

MKHal is an implementation of the [HAL specification](http://stateless.co/hal_specification.html) written in PHP. It can be used to build a HAL specific data structure and convert it to JSON or XML. It can be easily implemented in your current API backend workflow.

## Requirements

-   PHP 7.0 or higher

## Installation

You can clone or download the package and use it in your project or just install it via **composer**:

```bash
composer require mk/hal
```

## Usage

### Object types

MKHal offers different class types to represent the structure of a typical HAL implementation:

-   **HALObject:** The `HALObject` class represents the main resource in the HAL specification. It can hold normal data, `HALLink` objects and `HALCurie` objects. It can also embed additional `HALObjects` in referred by labels.
-   **HALLink:** The `HALLink` class represents a typical link resources in the \__links_ section of the HAL document. It can hold different information like the _href_ attribute. It get automatically serialized along with an `HALObject` instance. A `HALObject` can hold additional links after different labels.
-   **HALCurie:** The `HALCurie` class is the representation of the _curies_ attribute in HAL. An `HALObject` instance can have multiple curies inside of it.

### Basic usage

```php
use MK\HAL\HALObject;
use MK\HAL\HALLink;
use MK\HAL\HALCurie;

$hal = new HALObject('/orders');

$hal->addData(array(
    "offers" => 5,
    "prices" => array(
        "normal" => "10.99",
        "season" => "5.99"
    )
));

$hal->addCurie(new HALCurie('ea', 'http://example.com/docs/rels/{rel}'));

$hal->addLink('next', new HALLink('/orders?page=2'));

$hal->addLinkCollection('ea:admin', array(
    new HALLink('/admin/2'),
    new HALLink('/admin/5')
));

$product = new HALObject('/product/1');
$product->addLink('next', new HALLink('/product/2'));

$hal->embed('product', $product);

$hal->embedCollection('coupon', array(
    new HALObject('/coupon/5'),
    new HALObject('/coupon/6')
));

// use export() for json serialization
echo $hal->export(); // calls json_encode internally
// or simple encode the HALObject
echo json_encode($hal);
```

The call of the `export()` method creates a JSON representation of the HAL specification:

```json
{
    "_links": {
        "self": {
            "href": "/orders"
        },
        "curies": [
            {
                "name": "ea",
                "href": "http://example.com/docs/rels/{rel}",
                "templated": true
            }
        ],
        "next": {
            "href": "/orders?page=2"
        },
        "ea:admin": [
            {
                "href": "/admin/2"
            },
            {
                "href": "/admin/5"
            }
        ]
    },
    "offers": 5,
    "prices": {
        "normal": "10.99",
        "season": "5.99"
    },
    "_embedded": {
        "product": {
            "_links": {
                "self": {
                    "href": "/product/1"
                },
                "next": {
                    "href": "/product/2"
                }
            }
        },
        "coupon": [
            {
                "_links": {
                    "self": {
                        "href": "/coupon/5"
                    }
                }
            },
            {
                "_links": {
                    "self": {
                        "href": "/coupon/6"
                    }
                }
            }
        ]
    }
}
```

## Roadmap

-   [x] Implement basic HAL specification
-   [x] Enable _curies_
-   [ ] Auto create HAL data from given objects
-   [x] JSON export / output
-   [ ] XML export / output
-   [ ] Read HAL objects to use library as client too
-   [ ] Well structured class hierarchy
-   [ ] Slim Framework integration
