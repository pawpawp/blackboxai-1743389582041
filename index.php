<?php
require_once 'config.php';
require_once 'header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-12">Document Tracking System</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="incoming.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 px-8 rounded-lg text-xl transition duration-300 flex flex-col items-center">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                Incoming Documents
            </a>
            <a href="outgoing.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-8 rounded-lg text-xl transition duration-300 flex flex-col items-center">
                <i class="fas fa-paper-plane text-4xl mb-4"></i>
                Outgoing Documents
            </a>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>