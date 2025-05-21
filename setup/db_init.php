<?php

/* Init ------------------------------------------------------------------------- */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swe30003";

$conn= new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully<br>";


/* Open Data File ------------------------------------------------------------------------- */

$filename = "data.json";

if (!file_exists($filename))
{
    die("Error: File '$filename' does not exist");
}

$jsonString = file_get_contents('data.json');

if (!$jsonString)
{
    die("Error: Failed to read data from file '$jsonString'");
}

$data = json_decode($jsonString, true);

if (json_last_error() !== JSON_ERROR_NONE)
{
    die("Error: Failed to parse JSON string: '" . json_last_error_msg() . "'");
}

/* Delete Records ------------------------------------------------------------------------- */

$tables = ['orderline', 'products', 'categories'];

foreach ($tables as $table)
{
    $sql = "DELETE FROM $table";

    if ($conn->query($sql) !== TRUE)
    {
        die("Error: " . $sql . "<br>" . $conn->error);
    }
}

/* Add Categories ------------------------------------------------------------------------- */

foreach ($data["categories"] as $category)
{
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO categories (category_name, created_at, updated_at) VALUES ('$category', '$created_at', '$created_at')";
    if ($conn->query($sql) !== TRUE)
    {
        die("Error: " . $sql . "<br>" . $conn->error);
    }
}

echo "Categories added successfully<br>";

/* Add Products ------------------------------------------------------------------------- */

function addCatalogueCategoryProducts($category): void
{
    global $conn;
    global $data;

    foreach ($data["catalogue"][$category] as $product)
    {
        $name = $product["name"];
        $brand = $product["brand"];
        $description = $conn->real_escape_string($product["description"]);
        $price = $product["price"];
        $stock = $product["stock"];
        $category_name = $product["category_name"];
        $image_url = $product["image_url"];
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO products (name, brand, description, price, stock, category_name, image_url, created_at, updated_at)
                VALUES ('$name', '$brand', '$description', '$price', '$stock', '$category_name', '$image_url', '$created_at', '$created_at')";

        if ($conn->query($sql) !== TRUE)
        {
            die("Error: " . $sql . "<br>" . $conn->error);
        }
    }
}

addCatalogueCategoryProducts('phones');
addCatalogueCategoryProducts('tablets');
addCatalogueCategoryProducts('tvs');
addCatalogueCategoryProducts('laptops');
addCatalogueCategoryProducts('cameras');

echo "Products added successfully<br>";
