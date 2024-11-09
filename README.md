# clash-for-OPNsense
OPNsense上运行的命令行代理工具，在OPNsense 24.7上测试通过。

# 项目来源
源代码来自：
https://github.com/junyu33/clash-for-freebsd

订阅转换代码来自：
https://github.com/tindy2013/subconverter

Clash程序来自：
https://github.com/MetaCubeX/mihomo/releases，
可以下载最新版本替换bin目录下的clash程序。

# 使用须知

1、 运行脚本建议使用root用户，或者使用sudo提权。

2、 项目基于 [clash（mihomo）](https://github.com/MetaCubeX/mihomo/releases) 、[yacd](https://github.com/haishanh/yacd) 进行整合。

3、 脚本不提供任何订阅信息，请自行准备Clash订阅地址。

4、 运行脚本前请手动更改`.env`文件中的`CLASH_URL`变量值，否则无法正常运行。

5、 当前只支持x86_64平台。

# 使用教程

## 下载项目

以root用户登录OPNsense的shell环境。

1、安装依赖
```bash
pkg install git
pkg install bash
pkg add https://pkg.freebsd.org/FreeBSD:13:amd64/latest/All/zsh-5.9_4.pkg
pkg add https://pkg.freebsd.org/FreeBSD:14:amd64/latest/All/zsh-5.9_4.pkg
pkg add https://pkg.freebsd.org/FreeBSD:15:amd64/latest/All/zsh-5.9_4.pkg
```
> **注意：** `zsh`安装对应防火墙FreeBSD平台系统的版本。

2、下载项目
```bash
git clone https://github.com/fxn2020/clash-for-OPNsense.git
```
3、重命名文件夹
```bash
mv /root/clash-for-OPNsense /root/clash
```
4、添加订阅地址
```bash
cd clash
vi .env
```

> **注意：** `.env` 文件中的变量 `CLASH_SECRET` 为自定义访问安全密钥，为空时，脚本将自动生成随机字符串。

## 启动程序

1、启动脚本
```bash
bash start.sh

正在检测订阅地址...
Clash订阅地址可访问！   OK  

正在下载Clash配置文件...
配置文件config.yaml下载成功！   OK  

判断订阅内容是否符合clash配置文件标准:
订阅内容符合clash标准！

添加运行参数...
clash_enable: YES -> YES

启动Clash代理服务...
Starting clash.
代理启动成功！   OK  

Clash仪表盘访问地址：
http://<LAN ip>:9090/ui 
访问密钥: 123456 

命令说明：
开启代理: service clash start
关闭代理: service clash stop
重启代理: service clash restart
查看状态: service clash status
```
2、检查端口
```bash
netstat -f inet -na | grep -i LISTEN
tcp46      0      0 *.7891                 *.*                    LISTEN     
tcp46      0      0 *.7890                 *.*                    LISTEN     
tcp46      0      0 *.9090                 *.*                    LISTEN     
tcp4       0      0 127.0.0.1.953          *.*                    LISTEN     
tcp4       0      0 *.53                   *.*                    LISTEN     
tcp4       0      0 127.0.0.1.3129         *.*                    LISTEN     
tcp4       0      0 127.0.0.1.3128         *.*                    LISTEN     
tcp4       0      0 192.168.101.4.3128     *.*                    LISTEN 
```
如果7890和9090端口被占用，说明clash程序启动成功，现在客户端就可以通过代理上网了。

3、透明代理

[pfSense、OPNsense设置http透明代理教程](https://pfchina.org/?p=13572)

[pfSense、OPNsense配置tun2socks透明代理教程](https://pfchina.org/?p=13437)

## 控制面板

1、 通过地址`http://LAN IP:9090/ui`访问Clash控制面板。

2、 在`API Base URL`一栏中输入：http://\<ip\>:9090 ，在`Secret(optional)`一栏中输入访问安全密钥。

3、 点击`Add`，并单击刚刚输入的管理地址，之后便可以通过Web界面对clash进行配置。

# 常见问题

1、 必须使用 `bash xxx.sh` 运行脚本。

2、 部分用户在UI界面找不到代理节点，是因为机场提供的clash配置文件是经过base64编码的，且配置文件格式不符合clash配置标准所致。

3、 项目集成的订阅转换功能如果无法使用，可以通过自建或者第三方平台对订阅地址进行转换。

# 相关文章

[pfSense、OPNsense配置Xray代理教程](https://pfchina.org/?p=13013)

[pfSense、OPNsense配置trojan-go教程](https://pfchina.org/?p=9885)

[pfSense、OPNsense配置v2ray代理教程](https://pfchina.org/?p=4032)

[pfSense、OPNsense配置Clash代理教程](https://pfchina.org/?p=10526)

[pfSense、OPNsense配置hysteria代理教程](https://pfchina.org/?p=9524)

[pfSense、OPNsense配置sing-box代理教程](https://pfchina.org/?p=12933)

[pfSense、OPNsense配置http透明代理教程](https://pfchina.org/?p=13572)

[pfSense、OPNsense配置hysteria2代理教程](https://pfchina.org/?p=13065)

[pfSense、OPNsense配置tun2socks透明代理](https://pfchina.org/?p=13437)

[pfSense、OPNsense配置hysteria(TUN) 透明代理教程](https://pfchina.org/?p=13480)
