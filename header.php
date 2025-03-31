<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="https://images.pexels.com/photos/356044/pexels-photo-356044.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                         alt="Logo" class="h-10 w-10 rounded-full">
                    <span class="font-semibold text-xl">Document Tracking</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="incoming.php" class="hover:text-blue-300 transition duration-300">
                        <i class="fas fa-inbox mr-2"></i>Incoming
                    </a>
                    <a href="outgoing.php" class="hover:text-blue-300 transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Outgoing
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8">
        <?php flash(); ?>