<?php
// This installer scripts adds a product attribute to Magento programmatically.

// Set data:
$attributeName  = 'Restock Date'; // Name of the attribute
$attributeCode  = 'restock_date'; // Code of the attribute
$attributeGroup = 'Inventory';          // Group to add the attribute to
$attributeSetIds = array(4);          // Array with attribute set ID's to add this attribute to. (ID:4 is the Default Attribute Set)

// Configuration:
$data = array(
    'input'         => 'date',
    'type'          => 'datetime',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,    // Attribute scope
    'backend'   => "eav/entity_attribute_backend_datetime",
    'required'  => false,           // Is this attribute required?
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
    'used_in_product_listing' => true,
    // Filled from above:
    'label' => $attributeName
);

// Create attribute:
// We create a new installer class here so we can also use this snippet in a non-EAV setup script.
$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', $attributeCode, $data);

// Add the attribute to the proper sets/groups:
foreach($attributeSetIds as $attributeSetId)
{
    $installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);
}

// Done:
$installer->endSetup();