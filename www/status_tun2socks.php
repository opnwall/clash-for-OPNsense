<?php
header('Content-Type: application/json');

// 检查 Clash 运行状态
exec("pgrep -f '/usr/local/bin/tun2socks'", $output, $return_var);

if ($return_var === 0) {
    echo json_encode(["status" => "running"]);
} else {
    echo json_encode(["status" => "stopped"]);
}