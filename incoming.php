<?php
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO incoming (status, control_no, date_received, office_requestor, transaction_type, 
                              action_taken, date_forwarded, received_by, remarks) 
                              VALUES (:status, :control_no, :date_received, :office_requestor, :transaction_type, 
                              :action_taken, :date_forwarded, :received_by, :remarks)");
        
        $stmt->execute([
            ':status' => $_POST['status'],
            ':control_no' => $_POST['control_no'],
            ':date_received' => $_POST['date_received'],
            ':office_requestor' => $_POST['office_requestor'],
            ':transaction_type' => $_POST['transaction_type'],
            ':action_taken' => $_POST['action_taken'],
            ':date_forwarded' => $_POST['date_forwarded'] ?? null,
            ':received_by' => $_POST['received_by'],
            ':remarks' => $_POST['remarks'] ?? null
        ]);
        
        $_SESSION['success'] = 'Incoming document added successfully!';
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
    header('Location: incoming.php');
    exit();
}

// Get all incoming documents
try {
    $stmt = $conn->query("SELECT * FROM incoming ORDER BY date_received DESC");
    $incoming = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $_SESSION['error'] = 'Error fetching documents: ' . $e->getMessage();
    $incoming = [];
}
?>
<?php require_once 'header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Incoming Documents</h1>
    <a href="export.php?type=incoming" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        <i class="fas fa-file-excel mr-2"></i>Export to Excel
    </a>
</div>

<!-- Add New Form -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Add New Incoming Document</h2>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 mb-2" for="status">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Completed">Completed</option>
                <option value="On Hold">On Hold</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="control_no">Control No.</label>
            <input type="text" name="control_no" id="control_no" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="date_received">Date Received</label>
            <input type="date" name="date_received" id="date_received" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="office_requestor">Office/Requesting Party</label>
            <input type="text" name="office_requestor" id="office_requestor" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="transaction_type">Transaction Type</label>
            <select name="transaction_type" id="transaction_type" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="P.O.">P.O.</option>
                <option value="CONTRACT">Contract</option>
                <option value="PRS">PRS</option>
                <option value="PAR">PAR</option>
                <option value="ICS">ICS</option>
                <option value="CLEARANCE">Clearance</option>
                <option value="OTHER">Other</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="action_taken">Action Taken</label>
            <textarea name="action_taken" id="action_taken" rows="2" class="w-full px-3 py-2 border rounded-lg" required></textarea>
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="date_forwarded">Date Forwarded to R. Durana</label>
            <input type="date" name="date_forwarded" id="date_forwarded" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-gray-700 mb-2" for="received_by">Received By</label>
            <input type="text" name="received_by" id="received_by" class="w-full px-3 py-2 border rounded-lg" required>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Received</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($incoming as $row): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['control_no']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= date('M d, Y', strtotime($row['date_received'])) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['office_requestor']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['transaction_type']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $row['status'] === 'Completed' ? 'bg-green-100 text-green-800' : 
                               ($row['status'] === 'Processing' ? 'bg-blue-100 text-blue-800' : 
                               ($row['status'] === 'On Hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
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