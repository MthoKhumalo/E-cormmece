<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PRODUCTS </title>
    <link rel="stylesheet" href="CSS/admin_style.css">
        <script>
            document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".editable").forEach(item => {
                item.addEventListener("click", function() {
                    const originalText = this.innerText;
                    const type = this.getAttribute("data-type");
                    const id = this.getAttribute("data-id");

                    const input = document.createElement("input");
                    input.type = type === "price" || type === "quantity" ? "number" : "text";
                    input.value = originalText.replace('Quantity: ', '').replace('R ', ''); 
                    input.onblur = () => this.innerHTML = originalText; 

                    input.onkeydown = (event) => {
                        if (event.key === "Enter") {
                            const newValue = input.value;

                            // Show a confirmation prompt
                            const userConfirmed = confirm("Are you sure you want to save this change?");
                            if (userConfirmed) {
                                // Send the update to the server via AJAX
                                fetch("adminProducts/update_product.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        id: id,
                                        type: type,
                                        value: newValue
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.innerText = type === "quantity" ? `Quantity: ${newValue}` : newValue;
                                    } else {
                                        alert("Failed to update. Please try again.");
                                    }
                                })
                                .catch(error => console.error("Error:", error));
                            } else {
                                // If canceled, revert to the original text
                                this.innerHTML = originalText;
                            }
                        }
                    };

                    this.innerHTML = "";
                    this.appendChild(input);
                    input.focus();
                });
            });
        });

        </script>

    </head>

    <body>
        <!--header-->
        <?php  include 'adminHeader.php'; ?>

        <!--<div>
            <ul>
                <?php /* include '../searching.php' */ ?> <br>
            </ul>
        </div>-->

        <!--content-->

        <?php 
        
            include 'adminProducts/products_admin.inc.php';
            /*
            if (isset($_SESSION["search_results"])) {

                $products = $_SESSION["search_results"];
                search_products(products: $products);
                unset($_SESSION["search_results"]);

            }else{
                
                check_search_errors();
                include 'adminProducts/products_admin.inc.php';
            }*/
            
        ?>

    </body>  
</html>