Magento Restock Date
===========================

This module allows products that are on back order to be given a restock date, that is then displayed within both the product page 
and the cart. But once checkout is reeched the customer is redirected away to confirm they accept that the products are out of stock
before being redirected back to the checkout. And with so many people checking and changing products, we added the ability for 
alerts if modified outside of store hours. 

Installation
--------------------------------------

To install directly  via modman

```bash
modman clone https://github.com/danrcoull/restockdate.git 
```

To install using composer

```bash
{
    "repositories": [
        {
            "url": "https://github.com/danrcoull/restockdate.git",
            "type": "git"
        }
    ],
    "require": {
        "Gremlintech/Restockdate": "*"
    }
}
```

Configuration
--------------------------------------------

System -> Config -> Gremlintech -> Restockdate
And set Enabled to true
and turn on/off debug
