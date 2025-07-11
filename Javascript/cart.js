/* ajax code for the, add to cart, button. This allows the a customer to add products to 
    cart page without redirecting them to the another page or reloading the page */

$(document).ready(function() {
    $(".add-to-cart").click(function() {
        var productId = $(this).data("product-id");

        $.ajax({
            type: "POST",
            url: "database/cart.inc.php",
            data: {
                product_id: productId,
                action: "increase"
            },
            success: function(response) {
                try {
                    var result = JSON.parse(response.trim()); // Parse the JSON response
                    if (result.status === "failed") {
                        alert("Error adding item to cart: " + result.message);
                    } else {
                        alert("Item added to cart successfully!");
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error adding item to cart: ", error);
            }
        });
    });
});