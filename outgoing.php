<?php
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO outgoing (control_no, date, time, document, client_name, 
                              agency_office, contact_no, action_taken, acted_by, date_acted, remarks) 
                              VALUES (:control_no, :date, :time, :document, :client_name, 
                              :agency_office, :contact_no, :action_taken, :acted_by, :date_acted, :remarks)");
        
        $stmt->execute([
            ':control_no' => $_POST['control_no'],
            ':date' => $_POST['date'],
            ':time' => $_POST['time'],
            ':document' => $_POST['document'],
            ':client_name' => $_POST['client_name'],
            ':agency_office' => $_POST['agency_office'],
            ':contact_no' => $_POST['contact_no'] ?? null,
            ':action_taken' => $_POST['action_taken'],
            ':acted_by' => $_POST['acted_by'],
            ':date_acted' => $_POST['date_acted'],
            ':remarks' => $_POST['remarks'] ?? null
        ]);
        
        $_SESSION['success'] = 'Outgoing document added successfully!';
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
    header('Location: outgoing.php');
    exit();
}

// Get all outgoing documents
try {
    $stmt = $conn->query("SELECT * FROM outgoing ORDER BY date DESC, time DESC");
    $outgoing = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $_SESSION['error'] = 'Error fetching documents: ' . $e->getMessage();
    $outgoing = [];
}
?>
<?php require_once 'header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Outgoing Documents</h1>
    <a href="export.php?type=outgoing" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        <i class="fas fa-file-excel mr-2"></i>Export to Excel
    </a>
</div>

<!-- Add New Form -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Add New Outgoing Document</h2>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 mb-2" for="control_no">Control No.</label>
            <input type="text" name="control_no" id="control_no" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="date">Date</label>
            <input type="date" name="date" id="date" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="time">Time</label>
            <input type="time" name="time" id="time" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="document">Document</label>
            <input type="text" name="document" id="document" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="client_name">Client/Receiver Name</label>
            <input type="text" name="client_name" id="client_name" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="agency_office">Agency/Office/Address</label>
            <input type="text" name="agency_office" id="agency_office" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="contact_no">Contact No.</label>
            <input type="text" name="contact_no" id="contact_no" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="action_taken">Action Taken</label>
            <textarea name="action_taken" id="action_taken" rows="2" class="w-full px-3 py-2 border rounded-lg" required></textarea>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="acted_by">Acted By</label>
            <input type="text" name="acted_by" id="acted_by" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="date_acted">Date Acted</label>
            <input type="date" name="date_acted" id="date_acted" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i>Save Document
            </button>
        </div>
    </form>
</div>

<!-- Documents Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client/Receiver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency/Office</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($outgoing as $row): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['control_no']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?= date('M d, Y', strtotime($row['date'])) ?><br>
                        <?= date('h:i A', strtotime($row['time'])) ?>
                    </td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['document']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['client_name']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['agency_office']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></a>
                        <a href="#" class="text-green-600 hover:text-green-900 mr-3"><i class="fas fa-edit"></i></a>
                        <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>