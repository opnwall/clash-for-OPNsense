<?php
require_once("guiconfig.inc");
include("head.inc");
include("fbegin.inc");

$config_file = "/usr/local/etc/mosdns/config.yaml"; // MosDNS 配置文件路径
$log_file = "/var/log/mosdns.log";

// 服务控制函数
function handleServiceAction($action) {
    $allowedActions = ['start', 'stop', 'restart'];
    if (!in_array($action, $allowedActions)) {
        return "无效的操作！";
    }

    $cmd = "service mosdns " . escapeshellcmd($action);
    exec($cmd, $output, $return_var);

    $messages = [
        'start' => ["MosDNS 服务启动成功！", "MosDNS 服务启动失败！"],
        'stop' => ["MosDNS 服务已停止！", "MosDNS 服务停止失败！"],
        'restart' => ["MosDNS 服务重启成功！", "MosDNS 服务重启失败！"]
    ];
    return $return_var === 0 ? $messages[$action][0] : $messages[$action][1];
}

// 保存配置函数
function saveConfig($file, $content) {
    if (!is_writable($file)) {
        return "保存配置失败，请确保文件可写。";
    }

    return file_put_contents($file, $content) !== false 
        ? "配置保存成功！" 
        : "保存配置失败！";
}

// 处理表单提交
if ($_POST) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save_config') {
        $config_content = $_POST['config_content'] ?? '';
        $message = saveConfig($config_file, $config_content);
    } elseif ($action === 'clear_log') {
        file_put_contents($log_file, ""); // 清空日志文件
        $message = "日志已清空！";
    } else {
        $message = handleServiceAction($action);
    }
}

// 读取配置文件内容
$config_content = file_exists($config_file) ? htmlspecialchars(file_get_contents($config_file)) : "配置文件未找到！";
?>

<div>
    <?php if (!empty($message)): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
</div>

<section class="page-content-main">
    <div class="container-fluid">
        <div class="row">
            <!-- 服务状态 -->
            <section class="col-xs-12">
                <div class="content-box tab-content table-responsive __mb">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>服务状态</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="mosdns-status" class="alert alert-secondary">
                                        <i class="fa fa-circle-notch fa-spin"></i> 检查中...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 服务控制 -->
            <section class="col-xs-12">
                <div class="content-box tab-content table-responsive __mb">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>服务控制</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form method="post" class="form-inline">
                                        <button type="submit" name="action" value="start" class="btn btn-success">
                                            <i class="fa fa-play"></i> 启动
                                        </button>
                                        <button type="submit" name="action" value="stop" class="btn btn-danger">
                                            <i class="fa fa-stop"></i> 停止
                                        </button>
                                        <button type="submit" name="action" value="restart" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> 重启
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 配置管理 -->
            <section class="col-xs-12">
                <div class="content-box tab-content table-responsive __mb">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>配置管理</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form method="post">
                                        <textarea style="max-width:none" name="config_content" rows="10" class="form-control"><?= $config_content; ?></textarea>
                                        <br>
                                        <button type="submit" name="action" value="save_config" class="btn btn-primary">
                                            <i class="fa fa-save"></i> 保存配置
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 日志查看 -->
            <section class="col-xs-12">
                <div class="content-box tab-content table-responsive __mb">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>日志查看</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form method="post">
                                        <textarea style="max-width:none" id="log-viewer" rows="10" class="form-control" readonly></textarea>
                                        <br>
                                        <button type="submit" name="action" value="clear_log" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> 清空日志
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>
<script>
// 检查服务状态
function checkMosdnsStatus() {
    fetch('/status_mosdns.php', { cache: 'no-store' })
        .then(response => response.json())
        .then(data => {
            const statusElement = document.getElementById('mosdns-status');
            if (data.status === "running") {
                statusElement.innerHTML = '<i class="fa fa-check-circle text-success"></i> MosDNS 正在运行';
                statusElement.className = "alert alert-success";
            } else {
                statusElement.innerHTML = '<i class="fa fa-times-circle text-danger"></i> MosDNS 已停止';
                statusElement.className = "alert alert-danger";
            }
        });
}

// 实时刷新日志
function refreshLogs() {
    fetch('/status_mosdns_logs.php', { cache: 'no-store' })
        .then(response => response.text())
        .then(logContent => {
            const logViewer = document.getElementById('log-viewer');
            logViewer.value = logContent;
            logViewer.scrollTop = logViewer.scrollHeight;
        })
        .catch(error => {
            const logViewer = document.getElementById('log-viewer');
            logViewer.value += "\n[错误] 无法加载日志。\n";
            logViewer.scrollTop = logViewer.scrollHeight;
        });
}

// 页面加载时初始化
document.addEventListener('DOMContentLoaded', () => {
    checkMosdnsStatus();
    refreshLogs();
    setInterval(checkMosdnsStatus, 3000);
    setInterval(refreshLogs, 2000);
});
</script>

<?php include("foot.inc"); ?>
