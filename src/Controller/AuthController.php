<?php

namespace App\Controller;

use App\Traits\Database;
use PDO;
use PDOException;

class AuthController
{
    use Database;

    private $db;

    public static $message = "";

    public static $toastClass = "";

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function verifyLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Modify the query to select the username as well
            $stmt = $this->db->prepare("SELECT id, username, password FROM userdata WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $db_password = $row['password'];
                $user_id = $row['id'];
                $username = $row['username'];  // Fetch the username


                if (password_verify($password, $db_password)) {
                    self::$message = "Login successful";
                    self::$toastClass = "bg-success";

                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;


                    header("Location: /Blog/src/views/post_list.php");
                    exit();
                } else {
                    self::$message = "Incorrect password";
                    self::$toastClass = "bg-danger";
                }
            } else {
                self::$message = "Email not found";
                self::$toastClass = "bg-warning";
            }
        }
    }


    public function registerUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $checkEmailStmt = $this->db->prepare("SELECT email FROM userdata WHERE email = ?");
            $checkEmailStmt->execute([$email]);

            if ($checkEmailStmt->rowCount() > 0) {
                self::$message = "Email ID already exists";
                self::$toastClass = "#007bff";
            } else {
                $stmt = $this->db->prepare("INSERT INTO userdata (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $password])) {
                    self::$message = "Account created successfully";
                    self::$toastClass = "#28a745";
                } else {
                    $errorInfo = $stmt->errorInfo();
                    self::$message = "Error: " . $errorInfo[2];
                    self::$toastClass = "#dc3545";
                }
            }

            $checkEmailStmt = null;
            $stmt = null;
            $conn = null;
        }
    }

    public function logout()
    {
        session_start();

        $_SESSION = array();

        session_destroy();

        header("Location:  /Blog/src/views/login.php");
        exit();
    }

    public function updateProfile()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newName = trim($_POST['username']);
            $newEmail = trim($_POST['email']);

            if (empty($newName) || empty($newEmail)) {
                die("Name and Email cannot be empty.");
            }


            $stmt = $this->db->prepare("UPDATE userdata SET username = :name, email = :new_email WHERE email = :current_email");
            $stmt->execute([
                ':name' => $newName,
                ':new_email' => $newEmail,
                ':current_email' => $_SESSION['email']
            ]);


            $_SESSION['username'] = $newName;
            $_SESSION['email'] = $newEmail;

            header("Location: /Blog/src/views/view_profile.php");
            exit();
        }
    }


    public function changePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword !== $confirmPassword) {
                echo "<script>alert('New password and confirmation do not match.');</script>";
                return;
            }

            $email = $_SESSION['email'];


            $stmt = $this->db->prepare("SELECT password FROM userdata WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                echo "<script>alert('Current password is incorrect.');</script>";
                return;
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateStmt = $this->db->prepare("UPDATE userdata SET password = :password WHERE email = :email");
            $updateStmt->execute([
                'password' => $hashedPassword,
                'email' => $email
            ]);

            echo "<script>alert('Password updated successfully.');</script>";
            header("Location:  /Blog/src/views/login.php");
            exit();
        }


    }

}