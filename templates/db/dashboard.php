<?php
require_once 'db.php';

// Handle CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $quantity = $_POST['quantity'];
                
                if (addItem($conn, $name, $description, $category, $quantity)) {
                    echo "Item added successfully.";
                } else {
                    echo "Error adding item.";
                }
                break;
            
            case 'edit':
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $quantity = $_POST['quantity'];
                
                if (editItem($conn, $id, $name, $description, $category, $quantity)) {
                    echo "Item updated successfully.";
                } else {
                    echo "Error updating item.";
                }
                break;
            
            case 'delete':
                $id = $_POST['id'];
                
                if (deleteItem($conn, $id)) {
                    echo "Item deleted successfully.";
                } else {
                    echo "Error deleting item.";
                }
                break;
            
            case 'increment':
                $id = $_POST['id'];
                $increment = $_POST['increment'];
                
                if (incrementItemQuantity($conn, $id, $increment)) {
                    echo "Quantity updated successfully.";
                } else {
                    echo "Error updating quantity.";
                }
                break;
        }
    }
}

$categories = ['dairy products', 'beef', 'agricultural products'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        .category {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .items-grid {
            display: none;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .item {
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <?php foreach ($categories as $category): ?>
        <div class="category" onclick="toggleCategory('<?php echo $category; ?>')">
            <h2><?php echo ucfirst($category); ?></h2>
            <div id="<?php echo $category; ?>-grid" class="items-grid">
                <?php
                $items = getItemsByCategory($conn, $category);
                foreach ($items as $item):
                ?>
                    <div class="item">
                        <h3><?php echo $item['name']; ?></h3>
                        <p><?php echo $item['description']; ?></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <button onclick="editItem(<?php echo $item['id']; ?>)">Edit</button>
                        <button onclick="deleteItem(<?php echo $item['id']; ?>)">Delete</button>
                        <input type="number" id="increment-<?php echo $item['id']; ?>" min="1" value="1">
                        <button onclick="incrementItem(<?php echo $item['id']; ?>)">Add Quantity</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <h2>Add New Item</h2>
    <form id="addItemForm">
        <input type="text" name="name" placeholder="Item Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>"><?php echo ucfirst($category); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <button type="submit">Add Item</button>
    </form>

    <script>
        function toggleCategory(category) {
            const grid = document.getElementById(`${category}-grid`);
            grid.style.display = grid.style.display === 'none' ? 'grid' : 'none';
        }

        function editItem(id) {
            // Implement edit functionality (e.g., show a form with current values)
            alert('Edit functionality to be implemented');
        }

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                const form = new FormData();
                form.append('action', 'delete');
                form.append('id', id);
                
                fetch('', { method: 'POST', body: form })
                    .then(response => response.text())
                    .then(result => {
                        alert(result);
                        location.reload();
                    });
            }
        }

        function incrementItem(id) {
            const increment = document.getElementById(`increment-${id}`).value;
            const form = new FormData();
            form.append('action', 'increment');
            form.append('id', id);
            form.append('increment', increment);
            
            fetch('', { method: 'POST', body: form })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    location.reload();
                });
        }

        document.getElementById('addItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            form.append('action', 'add');
            
            fetch('', { method: 'POST', body: form })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    location.reload();
                });
        });
    </script>
</body>
</html>