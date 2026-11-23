<?php
/*
filename: manage.php
author: Wilson's Dream Team
description: HR manager page to list, filter, sort, update and delete EOIs.
*/

require_once __DIR__ . '/inc/auth.inc';
require_login();
require_once __DIR__ . '/settings.php';

function manage_escape($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function manage_post_value(string $key): string
{
    return trim((string) ($_POST[$key] ?? ''));
}

function manage_get_value(string $key): string
{
    return trim((string) ($_GET[$key] ?? ''));
}

$csrf_token = create_csrf_token();
$message = '';
$message_type = 'info';
$manager_name = (string) ($_SESSION['username'] ?? 'manager');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
$db_ready = $conn !== false;

if (!$db_ready) {
    error_log('DTShop manage: database connection failed.');
    $message = 'The management service is temporarily unavailable. Please try again later.';
    $message_type = 'error';
} else {
    mysqli_set_charset($conn, 'utf8mb4');

    // All operations that change data must use POST and a valid session CSRF token.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = manage_post_value('action');

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            $message = 'Your request could not be verified. Refresh the page and try again.';
            $message_type = 'error';
        } elseif ($action === 'delete') {
            $job_ref = strtoupper(manage_post_value('job_ref'));
            $confirmed = isset($_POST['confirm']) && $_POST['confirm'] === 'yes';

            if ($job_ref === '') {
                $message = 'Please enter a job reference to delete.';
                $message_type = 'error';
            } elseif (!$confirmed) {
                $message = 'Please confirm that you want to delete these EOI records.';
                $message_type = 'error';
            } elseif (!preg_match('/^[A-Z0-9]{5}$/', $job_ref)) {
                $message = 'Please enter a valid five-character job reference.';
                $message_type = 'error';
            } else {
                $stmt = mysqli_prepare($conn, 'DELETE FROM eoi WHERE job_ref = ?');
                if (!$stmt) {
                    error_log('DTShop manage: failed to prepare EOI delete.');
                    $message = 'The records could not be deleted. Please try again later.';
                    $message_type = 'error';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $job_ref);
                    if (mysqli_stmt_execute($stmt)) {
                        $deleted = mysqli_stmt_affected_rows($stmt);
                        $message = 'Deleted ' . $deleted . ' EOI record(s) for job reference ' . $job_ref . '.';
                        $message_type = 'success';
                    } else {
                        error_log('DTShop manage: EOI delete query failed.');
                        $message = 'The records could not be deleted. Please try again later.';
                        $message_type = 'error';
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        } elseif ($action === 'status') {
            $eoi_id = filter_var($_POST['eoi_id'] ?? null, FILTER_VALIDATE_INT);
            $status = manage_post_value('status');
            $allowed_statuses = ['New', 'Current', 'Final'];

            if ($eoi_id === false || $eoi_id === null || $eoi_id < 1 || !in_array($status, $allowed_statuses, true)) {
                $message = 'Invalid EOI number or status.';
                $message_type = 'error';
            } else {
                $stmt = mysqli_prepare($conn, 'UPDATE eoi SET status = ? WHERE EOInumber = ?');