<?php

class AdminController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        header('Location: index.php?controller=dashboard&action=admin');
        exit;
    }
}