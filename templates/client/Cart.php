<?php
class Cart {
    public function addToCart($product_id, $quantity) {
        session_start();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    public function removeFromCart($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function editQuantity($product_id, $new_quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $new_quantity;
        }
    }

    public function getTotal($products) {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $total += $products[$id]['price'] * $quantity;
            }
        }
        return $total;
    }

    public function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }
}
?>
