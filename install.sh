#!/bin/bash

##############  代理安装脚本  #################
echo -e ''
echo -e "\033[32m============ OPNsense 代理全家桶一键安装脚本 ==============\033[0m"
echo -e ''

# 定义颜色变量
GREEN="\033[32m"
YELLOW="\033[33m"
RED="\033[31m"
CYAN="\033[36m"
RESET="\033[0m"

# 定义目录变量
ROOT="/usr/local"
BIN_DIR="$ROOT/bin"
WWW_DIR="$ROOT/www"
CONF_DIR="$ROOT/etc"
MODELS_DIR="$ROOT/opnsense/mvc/app/models/OPNsense"
RC_DIR="$ROOT/etc/rc.d"
SYS_HOOK_START="$ROOT/etc/rc.syshook.d/start"
SYS_HOOK_EARLY="$ROOT/etc/rc.syshook.d/early"
PLUGINS="$ROOT/etc/inc/plugins.inc.d"
ACTIONS="$ROOT//opnsense/service/conf/actions.d"


# 定义日志函数
log() {
    local color="$1"
    local message="$2"
    echo -e "${color}${message}${RESET}"
}

# 创建目录
log "$YELLOW" "创建目录..."
mkdir -p "$CONF_DIR/sing-box" "$CONF_DIR/clash" "$CONF_DIR/clash/sub" "$CONF_DIR/clash/ui" "$CONF_DIR/tun2socks" "$CONF_DIR/mosdns" "$CONF_DIR/mosdns/rule" || log "$RED" "目录创建失败！"

# 复制文件
log "$YELLOW" "复制文件..."
log "$YELLOW" "生成菜单..."
log "$YELLOW" "生成服务..."
chmod +x bin/*
cp -f bin/* "$BIN_DIR/" || log "$RED" "bin 文件复制失败！"
cp -f www/* "$WWW_DIR/" || log "$RED" "www 文件复制失败！"
cp -R sub/* "$CONF_DIR/clash/sub/" || log "$RED" "sub 文件复制失败！"
cp -R rule/* "$CONF_DIR/mosdns/rule/" || log "$RED" "rule 文件复制失败！"
cp -R ui/* "$CONF_DIR/clash/ui/" || log "$RED" "ui 文件复制失败！"
cp -f plugins/* "$PLUGINS/" || log "$RED" "plugins 文件复制失败！"
cp -f actions/* "$ACTIONS/" || log "$RED" "actions 文件复制失败！"
cp -R menu/* "$MODELS_DIR/" || log "$RED" "menu 文件复制失败！"
cp rc.d/* "$RC_DIR/" || log "$RED" "rc.d 文件复制失败！"
cp geo/* "$CONF_DIR/mosdns/" || log "$RED" "geo 文件复制失败！"
cp geo/* "$CONF_DIR/clash/" || log "$RED" "geo 文件复制失败！"
cp conf/config_mosdns.yaml "$CONF_DIR/mosdns/config.yaml" || log "$RED" "mosdns 配置文件复制失败！"
cp conf/config_clash.yaml "$CONF_DIR/clash/config.yaml" || log "$RED" "clash 配置文件复制失败！"
cp conf/config_sing-box.json "$CONF_DIR/sing-box/config.json" || log "$RED" "sing-box 配置文件复制失败！"
cp conf/config_tun2socks.yaml "$CONF_DIR/tun2socks/config.yaml" || log "$RED" "tun2socks 配置文件复制失败！"

# 新建订阅程序
log "$YELLOW" "添加订阅程序..."
cat>/usr/bin/sub<<EOF
# 启动clash订阅程序
bash /usr/local/etc/clash/sub/sub.sh
EOF

# 安装zsh
log "$GREEN" "安装 zsh"
pkg add -f pkg/zsh-5.9_5.pkg
log "$GREEN" "zsh安装完成！"
echo ""

# 添加服务启动项
log "$YELLOW" "配置系统启用服务..."
sysrc singbox_enable="YES"
sysrc mosdns_enable="YES"
sysrc clash_enable="YES"
sysrc tun2socks_enable="YES"
echo ""
log "$GREEN" "注意：请备份/etc/rc.conf文件，以便在系统升级或重置后恢复插件功能。"
echo ""

# 设置开机启动脚本
log "$YELLOW" "设置开机启动脚本..."
cat>$SYS_HOOK_EARLY/11-tun2socks<<EOF
#!/bin/sh

# 在网络启用之前，先启用tun网卡，否则会造成tun接口所对应的虚拟网卡丢失。
service tun2socks start
EOF
cat>$SYS_HOOK_START/96-tun_if_up<<EOF
#!/bin/sh

# 设置执行权限
log "$YELLOW" "添加执行权限..."
for service in clash mosdns tun2socks; do
    chmod +x "$BIN_DIR/$service"
    chmod +x "$RC_DIR/$service"
	chmod +x /usr/bin/sub
	chmod +x /usr/local/etc/rc.d/singbox
	chmod +x /usr/local/bin/sing-box
	chmod +x $SYS_HOOK_EARLY/11-tun2socks
    chmod +x $SYS_HOOK_START/96-tun_if_up
done
echo ""

# 启用tun虚拟网卡
ifconfig tun_0 up
EOF
log "$GREEN" "OPNsense部分版本重启后tun虚拟网卡可能会down，需要设置开机时启用虚拟网卡。"
log "$GREEN" "脚本默认tun2socks生成的虚拟网卡名称为tun_0，如果更改，需要修改开机脚本。"
log "$GREEN" "或手动修改/usr/local/etc/rc.syshook.d/start/96-tun_if_up文件。"
echo ""

# 显示运行命令
log "$YELLOW" "服务运行命令..."
for service in singbox clash mosdns tun2socks; do
    log "$CYAN" "启动:   service $service start"
    log "$CYAN" "停止:   service $service stop"
    log "$CYAN" "重启:   service $service restart"
	log "$CYAN" "状态:   service $service status"
    echo ""
done

log "$YELLOW" "clash 订阅功能..."
log "$GREEN" "请在/usr/local/etc/clash/sub/env文件填入订阅地址。"
log "$GREEN" "修改/usr/local/etc/clash/sub/temp/templete_config.yaml订阅模板。"
log "$GREEN" "然后运行 sub 命令使用订阅，并参照博客文章添加自动订阅功能。"
echo ""

# 完成提示
log "$GREEN" "安装完成，请重启OPNsense防火墙，然后进入Web界面，导航到服务 > 代理面板菜单进行操作。"
echo ""
