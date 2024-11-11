<?php
if (!empty($_GET["action"])) {
	$productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
	$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';

	switch ($_GET["action"]) {
		case "add":
			if (!empty($quantity)) {
				$stmt = $db->prepare("SELECT * FROM dishes WHERE d_id = ?");
				$stmt->bind_param('i', $productId);
				$stmt->execute();
				$productDetails = $stmt->get_result()->fetch_object();

				if ($productDetails) {
					$res_id = $productDetails->rs_id;

					$discount_query = $db->prepare("SELECT discount FROM restaurant WHERE rs_id = ?");
					$discount_query->bind_param("s", $res_id);
					$discount_query->execute();
					$discount_result = $discount_query->get_result();
					$discount = $discount_result->fetch_assoc();
					$discount_value = isset($discount['discount']) ? $discount['discount'] : 0;

					$discounted_price = $productDetails->price - ($productDetails->price * ($discount_value / 100));


					$itemArray = array($productDetails->d_id => array(
						'res_id' => $res_id,
						'title' => $productDetails->title,
						'd_id' => $productDetails->d_id,
						'quantity' => $quantity,
						'price' => $discounted_price
					));

					if (!empty($_SESSION["cart_item"])) {
						if (in_array($productDetails->d_id, array_keys($_SESSION["cart_item"]))) {
							foreach ($_SESSION["cart_item"] as $k => $v) {
								if ($productDetails->d_id == $k) {
									$_SESSION["cart_item"][$k]["quantity"] += $quantity;
								}
							}
						} else {
							$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
						}
					} else {
						$_SESSION["cart_item"] = $itemArray;
					}
				} else {
					echo "Product not found.";
				}
			}

			break;

		case "remove":
			if (!empty($_SESSION["cart_item"])) {
				foreach ($_SESSION["cart_item"] as $k => $v) {
					if ($productId == $v['d_id'])
						unset($_SESSION["cart_item"][$k]);
				}
			}
			break;

		case "empty":
			unset($_SESSION["cart_item"]);
			break;

		case "check":
			header("location:checkout.php");
			break;
	}
}
