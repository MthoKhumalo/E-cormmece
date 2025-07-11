<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title>
    <link rel="stylesheet" href='../CSS/style.css'>
</head>
<body>
    <?php include 'adminHeader.php'; ?>
    <br>
    <h2>Update Products</h2>

    <div class="forms">
        <form action="adminProducts/add_product.php" method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea><br><br>

            <label for="price">Price:</label><br><br>
            <input type="number" id="price" name="price" min="0" step="0.01" required><br><br>

            <label for="quantity">Quantity:</label><br><br>
            <input type="number" id="quantity" name="quantity" min="1" step="1" required><br><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Monitor">Monitor</option>
                <option value="Laptop">Laptop</option>
                <option value="HDMI">HDMI</option>
                <option value="Headsets">Headsets</option>
            </select><br><br><br>

            <label for="image"></label>
            <input type="file" id="images" name="images[]" accept="image/*" required><br><br>
            <small>Upload an image for the product (JPEG or PNG format).</small><br><br>

            <input type="checkbox" id="confirm_changes" name="confirm_changes" value="yes">
            <label for="confirm_changes">I confirm that I'm sure about these changes</label><br><br>

            <button type="submit">Add Product</button>
        </form>
    </div>

    <script>
        function confirmSubmission() {
            const confirmChanges = document.getElementById('confirm_changes');
            if (!confirmChanges.checked) {
                alert('Please confirm that you are sure about these changes.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
