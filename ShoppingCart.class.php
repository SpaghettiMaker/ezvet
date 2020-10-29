<?php

class ShoppingCart
{
	private static $instance;
	private $fname = 'cartItems.json';
	public function __construct()
	{		
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
	  	{
			self::$instance = new self();
	  	}
	  	return self::$instance;
	}
	/**
	 * Get items from json file.
	 *
	 * @return json
	 */
	function get_data()
    {
        $json = '';

		if(!file_exists($this->fname)) 
		{
            file_put_contents($this->fname, '');
		} else 
		{
            $json = file_get_contents($this->fname);
        }

        return $json;
    }
	/**
	 * Get items in cart.
	 *
	 * @return array
	 */
    function getItems() 
    {
        $json = $this->get_data();
        return json_decode($json, true);
    }
    /**
	 * Get the total price of items in cart.
	 *
	 * @return float
	 */
    function getTotalPrice($products)
    {   
        $total = (float) 0;
        $items = $this->getItems();
		foreach($items as $key=>$value) 
		{
			foreach($products as $key2=>$value2) 
			{
				if ($products[$key2]["name"] == $key)
				{
					
                    $total += round($products[$key2]["price"], 2, PHP_ROUND_HALF_DOWN) * $value;
                }
            }
        }
        $total = number_format($total, 2, '.', ',');
        return $total;
	}
	/**
	 * Get the total price of items in cart.
	 *
	 * @return string
	 */
	function getPrice($products, $item)
    {   
		$price = (float) 0;
		foreach($products as $key=>$value) {
			if ($products[$key]["name"] == $item) {
				$price = $products[$key]["price"];
			}
		}
		
        return round($price, 2, PHP_ROUND_HALF_DOWN);
	}
	/**
	 * Add item to cart.
	 */
	function addItem($postItem) 
    {
        $key = array_key_first($postItem);
        $items = $this->getItems();
        $postItem[$key] = (int)$postItem[$key];
        
        if (array_key_exists($key, $items)) {
            $items[$key] += $postItem[$key];
        } else {
            $items += $postItem;
        }
        $this->set_data($items);
	}
	/**
	 * Set items to json.
	 */
	function set_data($arr) 
    {
        $json = json_encode($arr);
        file_put_contents($this->fname, $json);
	}
	/**
	 * Delete selected item by key from json.
	 */
	function deleteKey($key) 
    {
        $items = $this->getItems();
        unset($items[$key]);
        $this->set_data($items);
    }
	/**
	 * Remove all items from cart.
	 */
	public function clear()
	{
		$this->set_data('{}');
	}
}