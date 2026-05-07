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
├── database/               # SQLite 数据库存储
├── docker/
│   ├── nginx.conf          # Nginx 配置
│   └── supervisord.conf    # 进程管理配置
├── .dockerignore
├── .github/workflows/
│   └── publish.yml          # GitHub Actions 镜像发布工作流
├── Dockerfile
├── docker-compose.yml
└── composer.json
```

## 常用命令

| 操作             | 命令                                                 |
| ---------------- | ---------------------------------------------------- |
| 构建并启动       | `docker compose up --build`                          |
| 后台启动         | `docker compose up -d`                               |
| 停止             | `docker compose down`                                |
| 查看日志         | `docker compose logs -f`                             |
| 进入容器         | `docker compose exec app sh`                         |
| 安装依赖         | `docker compose exec app composer require <package>` |
| 不使用国内源构建 | `docker compose build --build-arg MIRROR_CN=false`   |

## 发布镜像到 GitHub Packages

项目配置了 GitHub Actions，推送 `v` 开头的 tag 时自动构建并发布镜像到 `ghcr.io`。

```bash
git tag v1.0.0
git push origin v1.0.0
```

镜像地址：`ghcr.io/<github用户名>/php-starter:latest`

## 添加 PHP 依赖

在容器内执行 Composer：

```bash
docker compose exec app composer require guzzlehttp/guzzle
```

> 务必在容器内执行，本地生成的 `vendor/` 目录会被 Docker 命名 volume 覆盖，容器无法读取。

## PHP 配置

**查看 PHP 配置信息：**

项目已包含 `public/phpinfo.php`，构建启动后访问 http://localhost:8080/phpinfo.php 即可查看完整的 PHP 配置。

也可在容器内通过命令行查看：

```bash
docker compose exec app php -i
```

> 上线前请删除 `phpinfo.php`，暴露该文件存在安全风险。

**自定义 PHP 配置：**

编辑 `docker/php.ini` 文件，重新构建后生效。PHP 会自动加载 `conf.d/` 目录下所有 `.ini` 文件。

## 开发说明

- 修改 `public/` 或 `src/` 下的文件后刷新浏览器即可生效（热重载）
- SQLite 数据库文件在 `database/app.db`，通过 volume 挂载到主机，重新构建或重启容器均不会丢失数据
- Nginx 只暴露 `public/` 目录，`src/` 和 `database/` 不可直接访问

## 技术栈

- PHP 8.4 (FPM)
- SQLite (PDO)
- Nginx
- Composer
- Docker + Supervisord
