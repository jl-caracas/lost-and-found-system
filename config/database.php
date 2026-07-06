<?php
/**
 * config/database.php – Database connection and auto-creation
 */

$host = "localhost";
$user = "root";
$password = "";
$database = "lost_and_found_db";

// Connect without database
$conn = mysqli_connect($host, $user, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

// Create tables if not exists
$sql_create_tables = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    id_type ENUM('pup_id','national_id','faculty_id','other') NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_initial VARCHAR(10) DEFAULT NULL,
    last_name VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    age INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    role ENUM('admin','staff','user') DEFAULT 'user',
    status ENUM('active','disabled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_id (id_type, id_number)
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    reward VARCHAR(100) DEFAULT NULL,
    status ENUM('lost','found') NOT NULL,
    location VARCHAR(255),
    specific_location VARCHAR(255) DEFAULT NULL,
    latitude DECIMAL(10, 8) DEFAULT NULL,
    longitude DECIMAL(11, 8) DEFAULT NULL,
    date_reported DATETIME NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    reported_by INT,
    status_label ENUM('open','found_owner','claimed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS item_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS claims (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    claimant_name VARCHAR(100) NOT NULL,
    claimant_id_type ENUM('pup_id','national_id','faculty_id','other') NOT NULL,
    claimant_id_number VARCHAR(50) NOT NULL,
    claimant_contact VARCHAR(20),
    proof_document VARCHAR(255),
    claim_date DATE NOT NULL,
    status ENUM('pending','approved','rejected','claimed') DEFAULT 'pending',
    admin_remarks TEXT,
    processed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    item_id INT NOT NULL,
    message TEXT NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    module VARCHAR(50),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS issue_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    issue_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'fixed') DEFAULT 'open',
    resolved_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
);
";

// Execute multi-query to create tables
if (mysqli_multi_query($conn, $sql_create_tables)) {
    do { } while (mysqli_next_result($conn));
} else {
    die("Error creating tables: " . mysqli_error($conn));
}

// Ensure the new columns are added to existing users table
$columns_to_add = [
    "first_name VARCHAR(100) NOT NULL AFTER email",
    "middle_initial VARCHAR(10) DEFAULT NULL AFTER first_name",
    "last_name VARCHAR(100) NOT NULL AFTER middle_initial",
    "birthdate DATE NOT NULL DEFAULT '2000-01-01' AFTER last_name",
    "age INT NOT NULL DEFAULT 0 AFTER birthdate"
];

foreach ($columns_to_add as $column_def) {
    $col_name = explode(" ", $column_def)[0];
    $check_col = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE '$col_name'");
    if (mysqli_num_rows($check_col) == 0) {
        mysqli_query($conn, "ALTER TABLE users ADD COLUMN $column_def");
    }
}

// Ensure new columns for items table
$item_columns_to_add = [
    "reward VARCHAR(100) DEFAULT NULL AFTER description"
];

foreach ($item_columns_to_add as $column_def) {
    $col_name = explode(" ", $column_def)[0];
    $check_col = mysqli_query($conn, "SHOW COLUMNS FROM items LIKE '$col_name'");
    if (mysqli_num_rows($check_col) == 0) {
        mysqli_query($conn, "ALTER TABLE items ADD COLUMN $column_def");
    }
}

// Insert default admin if not exists
$admin_check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'admin'");
if (mysqli_num_rows($admin_check) == 0) {
    $hashed = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status) 
                     VALUES ('admin', 'pup_id', 'ADMIN001', 'admin@foundly.com', 'Admin', 'User', '2000-01-01', 26, '$hashed', 'admin', 'active')";
    mysqli_query($conn, $insert_admin);
}

// Insert hardcoded staff user 1
$jhun_check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'jhun123'");
if (mysqli_num_rows($jhun_check) == 0) {
    $hashed_jhun = password_hash('jhun123', PASSWORD_DEFAULT);
    $insert_jhun = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status, profile_picture) 
                    VALUES ('jhun123', 'pup_id', '2024-00306-TG-0', 'jhun123@gmail.com', 'Jhun', 'Staff', '2000-01-01', 24, '$hashed_jhun', 'staff', 'active', 'jhun123ProfileImage.jpg')";
    mysqli_query($conn, $insert_jhun);
}

// Insert hardcoded staff user 2
$yuann_check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'yuann123'");
if (mysqli_num_rows($yuann_check) == 0) {
    $hashed_yuann = password_hash('yuann123', PASSWORD_DEFAULT);
    $insert_yuann = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status, profile_picture) 
                     VALUES ('yuann123', 'pup_id', '2024-00243-TG-0', 'yuann123@gmail.com', 'Yuann', 'Staff', '2000-01-01', 24, '$hashed_yuann', 'staff', 'active', 'yuann123ProfileImage.jpg')";
    mysqli_query($conn, $insert_yuann);
}

// Insert hardcoded user 1
$test1_check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'testuser1'");
if (mysqli_num_rows($test1_check) == 0) {
    $hashed_test1 = password_hash('testuser1', PASSWORD_DEFAULT);
    $insert_test1 = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status) 
                     VALUES ('testuser1', 'pup_id', 'TEST-00001-TG-0', 'testuser1@gmail.com', 'Test', 'User One', '2000-01-01', 24, '$hashed_test1', 'user', 'active')";
    mysqli_query($conn, $insert_test1);
}

// Insert hardcoded user 2
$test2_check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'testuser2'");
if (mysqli_num_rows($test2_check) == 0) {
    $hashed_test2 = password_hash('testuser2', PASSWORD_DEFAULT);
    $insert_test2 = "INSERT INTO users (username, id_type, id_number, email, first_name, last_name, birthdate, age, password, role, status) 
                     VALUES ('testuser2', 'pup_id', 'TEST-00002-TG-0', 'testuser2@gmail.com', 'Test', 'User Two', '2000-01-01', 24, '$hashed_test2', 'user', 'active')";
    mysqli_query($conn, $insert_test2);
}

// Insert default categories if none exist
$category_check = mysqli_query($conn, "SELECT id FROM categories LIMIT 1");
if (mysqli_num_rows($category_check) == 0) {
    $default_categories = [
        ["Personal Items", "Wallets, bags, clothing, accessories, jewelry, and other personal belongings."],
        ["Electronic", "Mobile phones, laptops, tablets, chargers, headphones, and other electronic devices."],
        ["Academic Items", "Books, notebooks, pens, uniforms, IDs, and other school-related items."],
        ["Perishable Items", "Foods: meat & seafood, dairy products, fresh fruits, vagetables, and other ready to eat items."],
        ["Pets", "Dogs, Cats, Fish, and other animals that was missing."]
    ];
    
    foreach ($default_categories as $cat) {
        $name = mysqli_real_escape_string($conn, $cat[0]);
        $desc = mysqli_real_escape_string($conn, $cat[1]);
        mysqli_query($conn, "INSERT INTO categories (name, description) VALUES ('$name', '$desc')");
    }
}

mysqli_set_charset($conn, "utf8");
?>