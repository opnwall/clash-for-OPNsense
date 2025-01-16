<?php
header('Content-Type: application/json');

// 检查 sing-box 服务状态
$service_status = trim(shell_exec("service singbox status"));

if (strpos($service_status, 'is running') !== false) {
    echo json_encode(['status' => 'running']);
} else {
    echo json_encode(['status' => 'stopped']);
}