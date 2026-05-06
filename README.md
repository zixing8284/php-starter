# PHP Starter

用 Docker 搭建 PHP 简单的开发环境，集成 PHP 8.4 + SQLite + Nginx。

## 快速开始

```bash
docker compose up --build
```

访问 http://localhost:8080 演示页面。

## 项目结构

```
├── public/
│   └── index.php          # Web 入口（Nginx 文档根目录）
├── src/
│   └── Database.php       # 应用代码
├── data/                   # SQLite 数据库存储
├── docker/
│   ├── nginx.conf          # Nginx 配置
│   └── supervisord.conf    # 进程管理配置
├── Dockerfile
├── docker-compose.yml
└── composer.json
```

## 常用命令

| 操作       | 命令                                                 |
| ---------- | ---------------------------------------------------- |
| 构建并启动 | `docker compose up --build`                          |
| 后台启动   | `docker compose up -d`                               |
| 停止       | `docker compose down`                                |
| 查看日志   | `docker compose logs -f`                             |
| 进入容器   | `docker compose exec app sh`                         |
| 安装依赖   | `docker compose exec app composer require <package>` |

## 添加 PHP 依赖

在容器内执行 Composer：

```bash
docker compose exec app composer require guzzlehttp/guzzle
```

> 务必在容器内执行，本地生成的 `vendor/` 目录会被 Docker 命名 volume 覆盖，容器无法读取。

## 开发说明

- 修改 `public/` 或 `src/` 下的文件后刷新浏览器即可生效（热重载）
- SQLite 数据库文件在 `data/app.db`，容器重启后数据保留
- Nginx 只暴露 `public/` 目录，`src/` 和 `data/` 不可直接访问

## 技术栈

- PHP 8.4 (FPM)
- SQLite (PDO)
- Nginx
- Composer
- Docker + Supervisord
