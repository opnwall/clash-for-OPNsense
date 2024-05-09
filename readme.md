## Clash for OPNsense（带订阅转换功能）

## 修改自以下项目
源代码：
https://github.com/junyu33/clash-for-freebsd

订阅转换部分代码引自：
https://github.com/tindy2013/subconverter

Clash可以用mihomo(meta)替换：
https://github.com/MetaCubeX/mihomo/releases

## 使用步骤：
将下载文件解压缩

# 修改订阅地址和Clash仪表板访问安全密钥
在.env文件CLASH_URL字段添加订阅地址,部分订阅地址可能无法正确转换。
在.env文件CLASH_SECRE字段输入访问密钥，留空则自动生复杂密钥。

# 安装bash、sudo、zsh，zsh根据自己防火墙的FreeBSD系统版本选择安装

pkg install bash
pkg install sudo
pkg add https://pkg.freebsd.org/FreeBSD:13:amd64/latest/All/zsh-5.9_4.pkg
pkg add https://pkg.freebsd.org/FreeBSD:14:amd64/latest/All/zsh-5.9_4.pkg
pkg add https://pkg.freebsd.org/FreeBSD:15:amd64/latest/All/zsh-5.9_4.pkg

# 上传并执行程序
将clash目录上传到防火墙的root目录下，然后进入clash目录并执行脚本：

cd clash
sudo bash start.sh

脚本启动完成后代理自动生效，并自动生成开机自启文件。

# 透明代理
请参考博客教程：
https://pfchina.org/?p=10626
https://pfchina.org/?p=10526

## 免费订阅地址（不保证长期有效）
https://neko-warp.nloli.xyz/neko_warp.yaml
https://subs.zeabur.app/clash


## 其他
部分用户在UI界面找不到代理节点，是因为机场提供的clash配置文件经过了base64编码的，
且配置文件格式不符合clash配置标准。如果订阅转换失败，则需要通过自建或者第三方平
台对订阅地址转换。