<?php
include_once "../models/User.php";

class UserController {
    public function listUsers() {
        $users = User::getAllUsers();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function listUser($id) {
        $users = User::getUserById($id);
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function deleteUser($id) {
        User::deleteUserById($id);
    }

    public function deleteWorker($id) {
        User::deleteWorkerById($id);
    }
}
?>
