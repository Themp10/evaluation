<?php
include "db.php";
global $conn;

function migratePasswords() {
    global $conn;

    // Ensure a valid connection
    if (!$conn) {
        die("Database connection error: " . $conn->connect_error);
    }

    // Fetch all users and their passwords
    $sql = "SELECT id, pwd FROM users";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error fetching users: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $plainPassword = $row['pwd'];
        
        // Hash the plaintext password
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        var_dump($hashedPassword);
        // Prepare and execute the update query
        $updateStmt = $conn->prepare("UPDATE users SET pwd = ? WHERE id = ?");
        if (!$updateStmt) {
            die("Error preparing update query: " . $conn->error);
        }

        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            echo "Password updated successfully for user ID: $userId\n";
        } else {
            echo "Failed to update password for user ID: $userId\n";
        }

        $updateStmt->close();
    }

    echo "Password migration complete.";
}
function hashThis(){
    $password = "1234";
    var_dump(password_hash($password, PASSWORD_DEFAULT));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if (password_verify($password, $hashedPassword)) {
        echo "Password match";
    }else{
        echo "Password does not match";
    }
}

//hashThis();
migratePasswords();
?>