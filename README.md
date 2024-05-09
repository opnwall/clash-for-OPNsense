# clash-for-OPNsense
OPNsense上运行的命令行代理工具，在OPNsense 24.1.6上测试成功。

# 项目介绍
源代码引自：
https://github.com/junyu33/clash-for-freebsd

订阅转换部分代码引自：
https://github.com/tindy2013/subconverter

Clash可以用mihomo(meta)替换：
https://github.com/MetaCubeX/mihomo/releases

免费订阅链接：
```bash
https://neko-warp.nloli.xyz/neko_warp.yaml
```
```bash
https://subs.zeabur.app/clash
```
<br>

# 使用须知

- 运行本项目建议使用root用户，或者使用sudo提权。
- 本项目是基于 [clash（mihomo）](https://github.com/MetaCubeX/mihomo/releases) 、[yacd](https://github.com/haishanh/yacd) 进行的配置整合。
- 此项目不提供任何订阅信息，请自行准备Clash订阅地址。
- 运行脚本前请手动更改`.env`文件中的`CLASH_URL`变量值，否则无法正常运行。
- 当前只支持x86_64平台。
<br>

# 使用教程

## 下载项目

以root用户进入OPNsense的shell环境，安装git程序
```bash
pkg install git
```

下载本项目

```bash
git clone https://github.com/fxn2020/clash-for-OPNsense.git
```
重命名文件夹

```bash
mv /root/clash-for-OPNsense /root/clash
```

进入clash目录，编辑`.env`文件，修改变量`CLASH_URL`的值。

```bash
cd clash
vi .env
```

> **注意：** `.env` 文件中的变量 `CLASH_SECRET` 为自定义 Clash Secret，值为空时，脚本将自动生成随机字符串。
<br>

## 启动程序

- 安装依赖
安装bash、zsh。

```bash
pkg install bash
pkg add https://pkg.freebsd.org/FreeBSD:13:amd64/latest/All/zsh-5.9_4.pkg
```
- 启动脚本

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
开启代理: sh /usr/local/etc/rc.d/clash start 
关闭代理: sh /usr/local/etc/rc.d/clash stop 
重启代理: sh /usr/local/etc/rc.d/clash restart 
查看状态: sh /usr/local/etc/rc.d/clash status 
```
- 检查端口
运行以下命令，检查端口占用：
```bash
netstat -f inet -na | grep -i LISTEN
```
显示内容如下：
```bash
tcp46      0      0 *.7891                 *.*                    LISTEN     
tcp46      0      0 *.7890                 *.*                    LISTEN     
tcp46      0      0 *.9090                 *.*                    LISTEN     
tcp4       0      0 127.0.0.1.953          *.*                    LISTEN     
tcp4       0      0 *.53                   *.*                    LISTEN     
tcp4       0      0 127.0.0.1.3129         *.*                    LISTEN     
tcp4       0      0 127.0.0.1.3128         *.*                    LISTEN     
tcp4       0      0 192.168.101.4.3128     *.*                    LISTEN 
```
如果7890和9090端口被占用，说明服务clash程序启动成功，现在客户端就可以通过代理上网了。

- 透明代理

OPNsense的透明代理设置，请参阅[鐵血男兒的BLOG](https://pfchina.org/?p=10526)。
<br>

## clash控制面板

- 访问clash控制面板

通过地址：`http://LAN IP:9090/ui`访问Clash控制面板。

- 登录管理界面

在`API Base URL`一栏中输入：http://\<ip\>:9090 ，在`Secret(optional)`一栏中输入访问安全密钥。

点击Add，并单击刚刚输入的管理界面地址，之后便可以通过Web管理界面对clash进行一些配置。

<br>

# 常见问题

1、必须使用 `bash xxx.sh` 运行脚本。

2、部分用户在UI界面找不到代理节点，是因为机场提供的clash配置文件是经过base64编码的，且配置文件格式不符合clash配置标准。

3、项目集成的订阅转换功能如果无法使用，可以通过自建或者第三方平台对订阅地址进行转换。

