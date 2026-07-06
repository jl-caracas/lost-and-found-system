<?php
/**
 * controllers/ProfileController.php – Profile Controller
 */

require_once __DIR__ . '/../models/User.php';

class ProfileController {
    private $userModel;
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->userModel = new User($db_connection);
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getById($user_id);
        
        include __DIR__ . '/../views/profile.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $first_name = trim($_POST['first_name'] ?? '');
            $middle_initial = trim($_POST['middle_initial'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $birthdate = $_POST['birthdate'] ?? '';
            $bio = trim($_POST['bio'] ?? '');
            $profilePicture = null;

            // Calculate age
            $age = 0;
            if (!empty($birthdate)) {
                $dob = new DateTime($birthdate);
                $now = new DateTime();
                $age = $now->diff($dob)->y;
            }

            // Handle file upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['profile_picture']['name']);
                $targetFilePath = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                        $profilePicture = $fileName;
                    } else {
                        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                        header("Location: index.php?action=profile");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
                    header("Location: index.php?action=profile");
                    exit();
                }
            }

            if ($this->userModel->updateProfile($user_id, $first_name, $middle_initial, $last_name, $birthdate, $age, $bio, $profilePicture)) {
                if ($profilePicture) {
                    $_SESSION['profile_picture'] = $profilePicture;
                }
                $_SESSION['success'] = "Profile updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }
            
            header("Location: index.php?action=profile");
            exit();
        }
    }

    public function publicProfile() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $user = $this->userModel->getById($id);
        if (!$user || $user['status'] !== 'active') {
            $_SESSION['error'] = "User not found or account is disabled.";
            header("Location: index.php?action=dashboard");
            exit();
        }

        // Include the public profile view
        include __DIR__ . '/../views/profile_public.php';
    }
}
?>
