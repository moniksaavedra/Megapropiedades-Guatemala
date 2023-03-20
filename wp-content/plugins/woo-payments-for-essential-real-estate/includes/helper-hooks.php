<?php
global $woocommerce;

//Put your code here that needs any woocommerce class
//You can also Instantiate your main plugin file here
/**
 * WC_Product_Data_Store_CPT class file.
 *
 * @package WooCommerce/Classes
 * @path woocommerce/includes/data-stores/class-wc-product-data-store-cpt.php
 */

// extend WC_Order_Item_Product class
class EPFL_WC_Order_Item_Product extends WC_Order_Item_Product
{
    public function set_product_id($value)
    {
        $this->set_prop('product_id', absint($value));
    }
}

add_filter('woocommerce_checkout_create_order_line_item_object', 'epfl_checkout_create_order_line_item_object', 10, 4);
function epfl_checkout_create_order_line_item_object($item, $cart_item_key, $values, $order)
{
    $product = $values['data'];
    if ($product) {
        $post_type = get_post_type($product->get_id());
        if (in_array($post_type, array('pricing_plan','cl_cpt','product'))) {
            return new EPFL_WC_Order_Item_Product();
        }
    }
    return $item;
}

add_filter('woocommerce_get_order_item_classname', 'epfl_get_order_item_classname', 10, 3);
function epfl_get_order_item_classname($classname, $item_type, $id)
{

    $item = new EPFL_WC_Order_Item_Product($id);
    $product_id = $item->get_product_id();

    if (in_array(get_post_type($product_id), array('pricing_plan','cl_cpt','product'))) {
        return 'EPFL_WC_Order_Item_Product';
    } else {
        return $classname;
    }
}
