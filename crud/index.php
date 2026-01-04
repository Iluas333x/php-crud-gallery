<?php
include("config.php");

/* =======================
   CREATE
======================= */
if(isset($_POST["create"])){

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $taxes = trim($_POST["taxes"]);
    $ads = trim($_POST["ads"]);
    $discount = trim($_POST["discount"]);
    $category = trim($_POST["category"]);
    $total = trim($_POST["total"]);

    if($title != '' && $price != '' && $category != ''){
        $query = "INSERT INTO crud (title, price, taxes, ads, discount, total, category)
                  VALUES ('$title','$price','$taxes','$ads','$discount','$total','$category')";
        mysqli_query($conn, $query);
        header("Location: index.php");
        exit;
    }
}

/* =======================
   DELETE
======================= */
if(isset($_GET['delete_id'])){
    $id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM crud WHERE id=$id");
    header("Location: index.php");
    exit;
}

/* =======================
   EDIT (GET DATA)
======================= */
$id = '';
$title = $price = $taxes = $ads = $discount = $category = '';

if(isset($_GET['edit_id'])){
    $id = (int)$_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM crud WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    $title = $row['title'];
    $price = $row['price'];
    $taxes = $row['taxes'];
    $ads = $row['ads'];
    $discount = $row['discount'];
    $category = $row['category'];
}

/* =======================
   UPDATE
======================= */
if(isset($_POST["update"])){
    $id = (int)$_POST["id"];

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $taxes = trim($_POST["taxes"]);
    $ads = trim($_POST["ads"]);
    $discount = trim($_POST["discount"]);
    $category = trim($_POST["category"]);
    $total = trim($_POST["total"]);

    if($title != '' && $price != '' && $category != ''){
        $query = "UPDATE crud SET
                    title='$title',
                    price='$price',
                    taxes='$taxes',
                    ads='$ads',
                    discount='$discount',
                    total='$total',
                    category='$category'
                  WHERE id=$id";
        mysqli_query($conn, $query);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CRUD Project</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #222;
    color: #fff;
    font-family: system-ui;
    padding: 10px;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 10px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    width: 100%;
    margin-bottom: 20px;
}

input, button {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    background: #000;
    color: red;
    border: none;
    border-radius: 5px;
    font-size: 16px;
}

input:focus {
    background: #444;
    outline: none;
}

button {
    background: #1f1e1e;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #400;
}

#total {
    background: red;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    margin: 5px 0;
    font-weight: bold;
}

#total::before {
    content: 'TOTAL: ';
}

/* Table Styles */
.table-container {
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    min-width: 600px;
}

th, td {
    padding: 10px;
    border: 1px solid #444;
}

th {
    background: #333;
    font-weight: bold;
}

td {
    background: #1a1a1a;
}

a {
    color: red;
    text-decoration: none;
    padding: 5px 10px;
    display: inline-block;
}

a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    body {
        padding: 5px;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    input, button {
        padding: 12px;
        font-size: 14px;
    }
    
    table {
        font-size: 12px;
        min-width: 500px;
    }
    
    th, td {
        padding: 8px 4px;
    }
}

@media screen and (max-width: 480px) {
    h2 {
        font-size: 1.2rem;
    }
    
    table {
        font-size: 10px;
        min-width: 400px;
    }
    
    th, td {
        padding: 6px 2px;
    }
    
    a {
        padding: 3px 5px;
        font-size: 10px;
    }
}

/* PRINT STYLES - Only show table */
@media print {
    /* Hide everything except table */
    h2, form, button {
        display: none !important;
    }
    
    body {
        background: white;
        color: black;
    }

    table {
        display: table;
        border-collapse: collapse;
        width: 100%;
        margin: 0;
    }

    table, th, td {
        border: 1px solid black;
    }
    
    th, td {
        padding: 8px;
    }
    
    /* Hide Update and Delete columns when printing */
    th:nth-child(6), th:nth-child(7),
    td:nth-child(6), td:nth-child(7) {
        display: none;
    }
}
</style>
</head>

<body>

<div class="container">
    <h2>CRUD PROJECT</h2>

    <form method="post">
        <input type="text" name="title" placeholder="Title" value="<?= $title ?>">
        <input type="number" name="price" placeholder="Price" value="<?= $price ?>" onkeyup="getTotal()">
        <input type="number" name="taxes" placeholder="Taxes" value="<?= $taxes ?>" onkeyup="getTotal()">
        <input type="number" name="ads" placeholder="Ads" value="<?= $ads ?>" onkeyup="getTotal()">
        <input type="number" name="discount" placeholder="Discount" value="<?= $discount ?>" onkeyup="getTotal()">
        <input type="text" name="category" placeholder="Category" value="<?= $category ?>">

        <div id="total"></div>
        <input type="hidden" name="total" id="totalinput">
        <input type="hidden" name="id" value="<?= $id ?>">

        <?php if($id): ?>
            <button name="update">Update</button>
        <?php else: ?>
            <button name="create">Create</button>
        <?php endif; ?>
    </form>

    <div class="table-container">
        <table border="1">
        <tr>
        <th>ID</th><th>Title</th><th>Price</th><th>Total</th><th>Category</th><th>Update</th><th>Delete</th>
        </tr>

        <?php
        $res = mysqli_query($conn, "SELECT * FROM crud");
        while($row = mysqli_fetch_assoc($res)){
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['price']}</td>
                <td>{$row['total']}</td>
                <td>{$row['category']}</td>
                <td><a href='index.php?edit_id={$row['id']}'>Edit</a></td>
                <td><a href='index.php?delete_id={$row['id']}' onclick='return confirm(\"Delete?\")'>Delete</a></td>
            </tr>";
        }
        ?>
        </table>
    </div>

    <button onclick="printPDF()">Print PDF</button>
</div>

<script>
function printPDF(){
    window.print();
}

function getTotal(){
    let price = +document.querySelector('[name="price"]').value || 0;
    let taxes = +document.querySelector('[name="taxes"]').value || 0;
    let ads = +document.querySelector('[name="ads"]').value || 0;
    let discount = +document.querySelector('[name="discount"]').value || 0;
    
    let result = price + taxes + ads - discount;
    
    let totalDiv = document.getElementById('total');
    let totalInput = document.getElementById('totalinput');
    
    if(price !== 0){
        totalDiv.innerHTML = result;
        totalDiv.style.background = 'green';
        totalInput.value = result;
    } else {
        totalDiv.innerHTML = '';
        totalDiv.style.background = 'red';
        totalInput.value = '';
    }
}
</script>

</body>
</html>