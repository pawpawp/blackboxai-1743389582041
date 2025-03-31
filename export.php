<?php
require_once 'config.php';

$type = $_GET['type'] ?? '';
$filename = '';

try {
    if ($type === 'incoming') {
        $stmt = $conn->query("SELECT * FROM incoming ORDER BY date_received DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filename = 'incoming_documents_' . date('Y-m-d') . '.xls';
    } elseif ($type === 'outgoing') {
        $stmt = $conn->query("SELECT * FROM outgoing ORDER BY date DESC, time DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $filename = 'outgoing_documents_' . date('Y-m-d') . '.xls';
    } else {
        throw new Exception('Invalid export type');
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $isPrintHeader = false;
    foreach ($data as $row) {
        if (!$isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
    }
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = 'Export failed: ' . $e->getMessage();
    header('Location: ' . ($type === 'incoming' ? 'incoming.php' : 'outgoing.php'));
    exit;
}
?>