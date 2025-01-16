## OPNsense Proxy Suite
A one-click installation program for running Clash, Sing-Box, Tun2socks, and Mosdns on OPNsense. It supports Clash subscription conversion, transparent proxying, and DNS splitting. Comes with a web control interface for configuration modifications, program control, log viewing, etc. Tested on OPNsense 24.7.11.

![](images/01.png)

## Project Source
This project integrates the following tools:

[Clash (mihomo)](https://github.com/MetaCubeX/mihomo/releases) 

[Sing-Box](https://github.com/SagerNet/sing-box) 

[Tun2socks](https://github.com/xjasonlyu/tun2socks) 

[Mosdns](https://github.com/IrineSistiana/mosdns) 

[YACD](https://github.com/haishanh/yacd) 

[Subconverter](https://github.com/tindy2013/subconverter)


## Notes
1. The script does not provide any subscription information. Please prepare your own Clash subscription URL. The example configuration is for reference only.

2. Before using the subscription feature, manually change the `CLASH_URL` variable in the `env` file; otherwise, the program will not run correctly.

3. Currently, only the x86_64 platform is supported.

## Installation
Download and extract the compressed package, upload it to the firewall, navigate to the directory, and run the following command:

```bash
sh install.sh
```

As shown in the figure below:  

![](images/02.png)

## Usage
Please refer to the following article:  
[OPNsense代理全家桶安装配置教程](https://pfchina.org/?p=14148)

## FAQs
If subscription conversion does not work, you can use [SublinkX](https://github.com/gooaclok819/sublinkX) to set up your own platform for subscription conversion.

## Related Articles
- [pfSense、OPNsense配置Xray代理教程](https://pfchina.org/?p=13013)  
- [pfSense、OPNsense配置trojan-go教程](https://pfchina.org/?p=9885)  
- [pfSense、OPNsense配置v2ray代理教程](https://pfchina.org/?p=4032)  
- [pfSense、OPNsense配置Clash代理教程](https://pfchina.org/?p=10526)  
- [pfSense、OPNsense配置hysteria代理教程](https://pfchina.org/?p=9524)  
- [pfSense、OPNsense设置http透明代理教程](https://pfchina.org/?p=13572)  
- [pfSense、OPNsense配置sing-box代理教程](https://pfchina.org/?p=12933)  
- [pfSense、OPNsense配置hysteria2代理教程](https://pfchina.org/?p=13065)  
- [pfSense、OPNsense配置tun2socks透明代理](https://pfchina.org/?p=13437)  
- [pfSense、OPNsense配置hysteria(TUN) 透明代理教程](https://pfchina.org/?p=13480)
