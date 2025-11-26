<?php
include 'config.php';
include_once __DIR__ . '/session_config.php';

$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
if (!$admin_id) {
    header('Location: login.php');
    exit;
}

// Get table name from URL
$table = isset($_GET['table']) ? mysqli_real_escape_string($conn, $_GET['table']) : '';

// Allowed tables (Whitelist for security)
$allowed_tables = ['products', 'users', 'author', 'publisher', 'message', 'blogs'];

if (in_array($table, $allowed_tables)) {

    // Define specific queries for complex tables (Joins)
    if ($table == 'products') {
        $query = "SELECT p.id, p.book_name, a.author_name, pub.publisher_name, p.price, p.stock_quantity, p.tag 
                  FROM products p 
                  LEFT JOIN author a ON p.author_id = a.id 
                  LEFT JOIN publisher pub ON p.publisher_id = pub.id";
        $headers = ['ID', 'Book Name', 'Author', 'Publisher', 'Price', 'Stock', 'Tag'];
    } elseif ($table == 'blogs') {
        $query = "SELECT b.id, b.title, a.author_name, c.name as category, b.created_at, b.views 
                  FROM blogs b 
                  LEFT JOIN author a ON b.author_id = a.id 
                  LEFT JOIN categories c ON b.category_id = c.id";
        $headers = ['ID', 'Title', 'Author', 'Category', 'Created At', 'Views'];
    } else {
        // Default: Select all columns for simple tables (users, author, publisher, message)
        $query = "SELECT * FROM `$table`";
        // We will fetch headers dynamically from the result keys later
        $headers = null;
    }

    // Execute Query
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Set Headers for Download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $table . '_export_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');

        // Fetch first row to determine headers if not set manually
        $first_row = mysqli_fetch_assoc($result);

        if (!$headers) {
            $headers = array_keys($first_row);
        }

        // Output Column Headings
        fputcsv($output, $headers);

        // Output First Row Data
        fputcsv($output, $first_row);

        // Output Rest of Data
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    } else {
        echo "No data found to export.";
    }
} else {
    echo "Invalid table selected.";
}
