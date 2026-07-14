#!/bin/bash
# ============================================
# BeikeShop One-Click Installation Script v3.0
# Supported Environments: BT Panel / Docker / Manual LNMP
# ============================================

set -e

# Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Default configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_NAME="${PROJECT_NAME:-$(basename "$SCRIPT_DIR")}"
DOMAIN="${DOMAIN:-}"
USER_DB_NAME="${DB_NAME:-}"
USER_DB_USER="${DB_USER:-}"
DEFAULT_DB_IDENTIFIER="$(printf '%s' "$PROJECT_NAME" | tr '[:upper:]-' '[:lower:]_' | sed 's/[^a-z0-9_]/_/g; s/^_*//; s/_*$//; s/__*/_/g' | cut -c1-24)"
DEFAULT_DB_IDENTIFIER="${DEFAULT_DB_IDENTIFIER:-beikeshop}"
DB_NAME="${DB_NAME:-$DEFAULT_DB_IDENTIFIER}"
DB_USER="${DB_USER:-$DEFAULT_DB_IDENTIFIER}"
USER_DB_PASSWORD="${DB_PASSWORD:-}"
USER_DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-}"
DB_PASSWORD="${DB_PASSWORD:-$(openssl rand -base64 12 | tr -d '/+=' | head -c 16)}"
DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-***}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@beikeshop.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-$(openssl rand -base64 12 | tr -d '/+=' | head -c 16)}"
BEIKESHOP_VERSION="${BEIKESHOP_VERSION:-$(grep "'version'" beike/Config/beike.php 2>/dev/null | sed -E "s/.*'version'.*=>\s*'([^']+)'.*/\1/" || echo '1.6.0')}"
BEIKESHOP_URL="${BEIKESHOP_URL:-https://github.com/beikeshop/beikeshop/releases/download/v${BEIKESHOP_VERSION}/beikeshop_v${BEIKESHOP_VERSION}.zip}"
BEIKESHOP_REPO="${BEIKESHOP_REPO:-https://github.com/beikeshop/beikeshop.git}"
INSTALL_MODE="${INSTALL_MODE:-release}"  # release or source

# Environment type
ENV_TYPE=""
WEB_ROOT=""
PHP_UPSTREAM=""
BT_PHP_BIN=""
BT_PHP_VERSION=""
BT_PHP_SOCKET=""
BT_PANEL_PYTHON=""

# ============================================
# Utility Functions
# ============================================

print_info()    { echo -e "${BLUE}[INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}[✓]${NC} $1"; }
print_warn()    { echo -e "${YELLOW}[!]${NC} $1"; }
print_error()   { echo -e "${RED}[✗]${NC} $1"; }

print_banner() {
    echo ""
    echo -e "${GREEN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║       BeikeShop One-Click Installer v3.0 ║${NC}"
    echo -e "${GREEN}║  Supports: BT Panel / Docker / Manual    ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════╝${NC}"
    echo ""
}

read_env_value() {
    local key="$1"
    local file="${2:-.env}"

    if [ ! -f "$file" ]; then
        return
    fi

    grep -E "^${key}=" "$file" | tail -1 | cut -d '=' -f2-
}

detect_bt_php_runtime() {
    local php_bin="${BT_PHP_BIN:-/www/server/php/82/bin/php}"

    if [ ! -x "$php_bin" ]; then
        php_bin=$(find /www/server/php -maxdepth 2 -path '*/bin/php' 2>/dev/null | sort -V | tail -1)
    fi

    if [ -z "$php_bin" ] || [ ! -x "$php_bin" ]; then
        return 1
    fi

    BT_PHP_BIN="$php_bin"
    BT_PHP_VERSION="$(basename "$(dirname "$(dirname "$php_bin")")")"
    BT_PHP_SOCKET="/tmp/php-cgi-${BT_PHP_VERSION}.sock"

    if [ ! -S "$BT_PHP_SOCKET" ]; then
        local detected_socket
        detected_socket=$(find /tmp -maxdepth 1 -name "php-cgi-${BT_PHP_VERSION}*.sock" 2>/dev/null | head -1)
        if [ -n "$detected_socket" ]; then
            BT_PHP_SOCKET="$detected_socket"
        fi
    fi

    return 0
}

detect_manual_php_upstream() {
    local socket_candidates=(
        "/run/php/php-fpm.sock"
        "/run/php/php8.2-fpm.sock"
        "/run/php/php8.3-fpm.sock"
        "/run/php/php8.4-fpm.sock"
        "/var/run/php/php-fpm.sock"
        "/var/run/php/php8.2-fpm.sock"
        "/var/run/php/php8.3-fpm.sock"
        "/var/run/php/php8.4-fpm.sock"
        "/var/run/php-fpm/php-fpm.sock"
        "/run/php-fpm/www.sock"
    )
    local socket_path

    for socket_path in "${socket_candidates[@]}"; do
        if [ -S "$socket_path" ]; then
            PHP_UPSTREAM="unix:${socket_path}"
            return 0
        fi
    done

    if command -v php-fpm >/dev/null 2>&1; then
        local php_fpm_listen
        php_fpm_listen=$(php-fpm -tt 2>&1 | sed -n 's|.*listen = \(.*\)$|\1|p' | head -1)
        if [ -n "$php_fpm_listen" ]; then
            if [[ "$php_fpm_listen" == /* ]]; then
                PHP_UPSTREAM="unix:${php_fpm_listen}"
            else
                PHP_UPSTREAM="$php_fpm_listen"
            fi
            return 0
        fi
    fi

    PHP_UPSTREAM="unix:/run/php/php-fpm.sock"
    return 0
}

detect_bt_panel_python() {
    if [ -n "$BT_PANEL_PYTHON" ] && [ -x "$BT_PANEL_PYTHON" ]; then
        return 0
    fi

    for python_bin in \
        /www/server/panel/pyenv/bin/python3 \
        /www/server/panel/pyenv/bin/python \
        "$(command -v python3 2>/dev/null)" \
        "$(command -v python 2>/dev/null)"; do
        if [ -n "$python_bin" ] && [ -x "$python_bin" ]; then
            BT_PANEL_PYTHON="$python_bin"
            return 0
        fi
    done

    return 1
}

bt_api_create_site() {
    if ! detect_bt_panel_python; then
        print_warn "BT Panel Python runtime not found."
        return 1
    fi

    local result

    result="$(
        DOMAIN="$DOMAIN" \
        WEB_ROOT="$WEB_ROOT" \
        BT_PHP_VERSION="$BT_PHP_VERSION" \
        PROJECT_NAME="$PROJECT_NAME" \
        DB_USER="$DB_USER" \
        DB_PASSWORD="$DB_PASSWORD" \
        "$BT_PANEL_PYTHON" - <<'PY'
import json
import os
import sys
import shutil

sys.path.insert(0, "/www/server/panel/class")
sys.path.insert(0, "/www/server/panel")

import public
import panelSite


class Obj(dict):
    def __getattr__(self, item):
        if item in self:
            return self[item]
        raise AttributeError(item)

    __setattr__ = dict.__setitem__


get = Obj()
get.path = os.environ["WEB_ROOT"]
get.webname = json.dumps({
    "domain": os.environ["DOMAIN"],
    "domainlist": [],
    "count": 0,
}, ensure_ascii=False)
get.port = "80"
get.version = os.environ["BT_PHP_VERSION"]
get.ps = os.environ["PROJECT_NAME"]
get.ftp = "false"
get.sql = "true"
get.codeing = "utf8mb4"
get.datauser = os.environ["DB_USER"]
get.datapassword = os.environ["DB_PASSWORD"]
get.type_id = "0"
get.type = "PHP"
get.setdefault = False
get.deploy_type = ""

site = panelSite.panelSite()
result = site.AddSite(get)

if result.get("siteStatus"):
    site_id = result.get("siteId")
    site_name = public.M("sites").where("id=?", (site_id,)).getField("name")
    site_path = public.M("sites").where("id=?", (site_id,)).getField("path")

    run_path_get = Obj(id=site_id, runPath="/public")
    result["setRunPath"] = site.SetSiteRunPath(run_path_get)

    basedir_get = Obj(id=site_id, path=site_path)
    result["setDirUserINI"] = site.SetDirUserINI(basedir_get)

    webserver = public.get_webserver()
    rewrite_server = "apache" if webserver == "openlitespeed" else webserver
    template_file = f"/www/server/panel/rewrite/{rewrite_server}/laravel5.conf"
    rewrite_file = f"/www/server/panel/vhost/rewrite/{site_name}.conf"

    if os.path.exists(template_file):
        with open(template_file, "r", encoding="utf-8", errors="ignore") as fp:
            rewrite_data = fp.read()
    else:
        if rewrite_server == "apache":
            rewrite_data = """<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
"""
        else:
            rewrite_data = """location / {
    try_files $uri $uri/ /index.php?$query_string;
}
"""

    rewrite_get = Obj(
        sites=json.dumps([{"id": site_id, "file": rewrite_file}], ensure_ascii=False),
        rewrite_data=rewrite_data,
    )
    result["setRewrite"] = site.SetRewriteLists(rewrite_get)

    public.serviceReload()

print(json.dumps(result, ensure_ascii=False))
PY
    )" || {
        print_warn "BT Panel internal site creation failed."
        return 1
    }

    if ! printf '%s' "$result" | php -r '
        $data = json_decode(stream_get_contents(STDIN), true);
        if (!is_array($data) || empty($data["siteStatus"]) || empty($data["databaseStatus"])) {
            exit(1);
        }
        foreach (["setRunPath", "setDirUserINI", "setRewrite"] as $key) {
            if (isset($data[$key]["status"]) && !$data[$key]["status"]) {
                exit(1);
            }
        }
    '; then
        local message
        message="$(printf '%s' "$result" | php -r '
            $data = json_decode(stream_get_contents(STDIN), true);
            if (!is_array($data)) {
                echo "Invalid BT Panel response";
                exit;
            }
            $messages = [];
            if (empty($data["siteStatus"])) {
                $messages[] = "BT site creation failed";
            }
            if (empty($data["databaseStatus"])) {
                $messages[] = "BT database creation failed";
            }
            foreach (["msg", "setRunPath", "setDirUserINI", "setRewrite"] as $key) {
                if ($key === "msg") {
                    if (!empty($data[$key])) {
                        $messages[] = $data[$key];
                    }
                    continue;
                }
                if (!empty($data[$key]["msg"])) {
                    $messages[] = $data[$key]["msg"];
                }
            }
            echo $messages ? implode(" | ", array_unique($messages)) : "Unknown error";
        ')"
        print_warn "BT Panel site setup failed: ${message}"
        return 1
    fi

    if ! printf '%s' "$result" | php -r '
        $data = json_decode(stream_get_contents(STDIN), true);
        if (!is_array($data)) {
            exit(1);
        }
    '; then
        print_warn "BT Panel returned an invalid response."
        return 1
    fi

    print_success "BT Panel site and database created"
    return 0
}

precheck_baota_install_target() {
    if ! detect_bt_panel_python; then
        print_error "BT Panel Python runtime not found"
        exit 1
    fi

    local result
    result="$(
        DOMAIN="$DOMAIN" \
        WEB_ROOT="$WEB_ROOT" \
        DB_NAME="$DB_NAME" \
        DB_USER="$DB_USER" \
        "$BT_PANEL_PYTHON" - <<'PY'
import json
import os
import sqlite3
import sys

sys.path.insert(0, "/www/server/panel/class")
sys.path.insert(0, "/www/server/panel")

domain = os.environ["DOMAIN"]
web_root = os.environ["WEB_ROOT"]
db_name = os.environ["DB_NAME"]
db_user = os.environ["DB_USER"]

result = {
    "db_name": db_name,
    "db_user": db_user,
    "sites": [],
    "panel_databases": [],
    "mysql_databases": [],
    "mysql_users": [],
}

def query_sqlite(db_path, sql, params):
    if not os.path.exists(db_path):
        return []
    conn = sqlite3.connect(db_path)
    try:
        return conn.execute(sql, params).fetchall()
    finally:
        conn.close()

result["sites"] = query_sqlite(
    "/www/server/panel/data/db/site.db",
    "select id,name,path from sites where name=? or path=?",
    (domain, web_root),
)
result["panel_databases"] = query_sqlite(
    "/www/server/panel/data/db/database.db",
    "select id,name,username from databases where name=? or username=?",
    (db_name, db_user),
)

try:
    import public
    mysql = public.get_mysql_obj_by_sid(0)
    safe_db_name = db_name.replace("\\", "\\\\").replace("'", "\\'")
    safe_db_user = db_user.replace("\\", "\\\\").replace("'", "\\'")
    result["mysql_databases"] = mysql.query("show databases like '%s'" % safe_db_name)
    result["mysql_users"] = mysql.query("select user,host from mysql.user where user='%s'" % safe_db_user)
except Exception as e:
    result["mysql_error"] = str(e)

print(json.dumps(result, ensure_ascii=False))
PY
    )" || {
        print_error "Failed to check BT Panel existing site/database state"
        exit 1
    }

    local has_conflict=0

    while IFS= read -r line; do
        [ -n "$line" ] || continue
        print_error "$line"
        has_conflict=1
    done < <(printf '%s' "$result" | php -r '
        $data = json_decode(stream_get_contents(STDIN), true);
        if (!is_array($data)) {
            echo "Invalid BT Panel precheck response\n";
            exit;
        }

        foreach ($data["sites"] ?? [] as $site) {
            $id = $site[0] ?? "";
            $name = $site[1] ?? "";
            $path = $site[2] ?? "";
            echo "BT Panel site already exists: {$name} (ID: {$id}, path: {$path})\n";
        }

        foreach ($data["panel_databases"] ?? [] as $database) {
            $id = $database[0] ?? "";
            $name = $database[1] ?? "";
            $user = $database[2] ?? "";
            echo "BT Panel database already exists: {$name} (ID: {$id}, user: {$user})\n";
        }

        if (!empty($data["mysql_databases"])) {
            echo "MySQL database already exists: " . ($data["db_name"] ?? "") . "\n";
        }

        if (!empty($data["mysql_users"])) {
            $hosts = array_map(static fn($row) => $row[1] ?? "", $data["mysql_users"]);
            echo "MySQL user already exists: " . ($data["db_user"] ?? "") . "@" . implode(",", array_filter($hosts)) . "\n";
        }

        if (!empty($data["mysql_error"])) {
            echo "Unable to check MySQL state from BT Panel: {$data["mysql_error"]}\n";
        }
    ')

    if [ "$has_conflict" = "1" ]; then
        echo ""
        echo "The installer stopped before creating resources to avoid overwriting an existing BT Panel site or database."
        echo "Please choose another DOMAIN/PROJECT_NAME/DB_NAME/DB_USER, or clean the existing resources manually before rerunning."
        exit 1
    fi
}

resolve_baota_database_names() {
    if [ -n "$USER_DB_NAME" ] || [ -n "$USER_DB_USER" ]; then
        return
    fi

    if ! detect_bt_panel_python; then
        print_error "BT Panel Python runtime not found"
        exit 1
    fi

    local result
    result="$(
        BASE_NAME="$DB_NAME" \
        BASE_USER="$DB_USER" \
        "$BT_PANEL_PYTHON" - <<'PY'
import json
import os
import re
import sqlite3
import sys

sys.path.insert(0, "/www/server/panel/class")
sys.path.insert(0, "/www/server/panel")

base_name = os.environ["BASE_NAME"]
base_user = os.environ["BASE_USER"]

def normalize(value):
    value = re.sub(r"[^a-zA-Z0-9_]", "_", value).strip("_")
    value = re.sub(r"_+", "_", value)
    return (value or "beikeshop")[:24]

base_name = normalize(base_name)
base_user = normalize(base_user)

def sqlite_values(db_path, sql):
    if not os.path.exists(db_path):
        return set()
    conn = sqlite3.connect(db_path)
    try:
        return {str(row[0]) for row in conn.execute(sql).fetchall()}
    finally:
        conn.close()

panel_db_names = sqlite_values("/www/server/panel/data/db/database.db", "select name from databases")
panel_db_users = sqlite_values("/www/server/panel/data/db/database.db", "select username from databases")
mysql_db_names = set()
mysql_users = set()

try:
    import public
    mysql = public.get_mysql_obj_by_sid(0)
    mysql_db_names = {row[0] for row in mysql.query("show databases") if row and row[0]}
    mysql_users = {row[0] for row in mysql.query("select distinct user from mysql.user") if row and row[0]}
except Exception as e:
    print(json.dumps({"error": str(e)}, ensure_ascii=False))
    sys.exit(0)

used_names = panel_db_names | mysql_db_names
used_users = panel_db_users | mysql_users

def available(base, used):
    for index in range(1, 100):
        suffix = "" if index == 1 else "_%s" % index
        max_base_length = 32 - len(suffix)
        candidate = base[:max_base_length] + suffix
        if candidate not in used:
            return candidate
    raise RuntimeError("No available database identifier found")

db_name = available(base_name, used_names)
db_user = available(base_user, used_users)

print(json.dumps({"db_name": db_name, "db_user": db_user}, ensure_ascii=False))
PY
    )" || {
        print_error "Failed to resolve available BT database name"
        exit 1
    }

    if printf '%s' "$result" | php -r '
        $data = json_decode(stream_get_contents(STDIN), true);
        exit(is_array($data) && empty($data["error"]) && !empty($data["db_name"]) && !empty($data["db_user"]) ? 0 : 1);
    '; then
        local resolved_db_name
        local resolved_db_user

        resolved_db_name="$(printf '%s' "$result" | php -r '$data = json_decode(stream_get_contents(STDIN), true); echo $data["db_name"] ?? "";')"
        resolved_db_user="$(printf '%s' "$result" | php -r '$data = json_decode(stream_get_contents(STDIN), true); echo $data["db_user"] ?? "";')"

        if [ "$resolved_db_name" != "$DB_NAME" ] || [ "$resolved_db_user" != "$DB_USER" ]; then
            print_warn "Default database name is occupied, using ${resolved_db_name}/${resolved_db_user}"
        fi

        DB_NAME="$resolved_db_name"
        DB_USER="$resolved_db_user"
        return
    fi

    print_error "Unable to resolve available BT database name: ${result}"
    exit 1
}

configure_domain() {
    if [ -n "$DOMAIN" ]; then
        return
    fi

    local input_domain

    echo "Enter the website domain or IP. Press Enter to use localhost."
    read -r -p "Website domain: " input_domain
    DOMAIN="${input_domain:-localhost}"
}

run_mysql_as_root() {
    local mysql_bin="${1:-mysql}"
    local password="${DB_ROOT_PASSWORD:-}"

    if [ -n "$password" ] && [ "$password" != "***" ]; then
        MYSQL_PWD="$password" "$mysql_bin" -uroot
        return
    fi

    if "$mysql_bin" -uroot -e "SELECT 1;" >/dev/null 2>&1; then
        "$mysql_bin" -uroot
        return
    fi

    read -r -s -p "Enter MySQL root password: " password < /dev/tty
    echo
    MYSQL_PWD="$password" "$mysql_bin" -uroot
}

install_project_dependencies() {
    if [ ! -d "vendor" ]; then
        print_info "Installing Composer dependencies..."
        COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader
    elif [ "$INSTALL_MODE" = "release" ]; then
        print_info "Release package mode, composer dependencies already exist"
    fi

    if [ -f "package.json" ] && [ ! -f "public/mix-manifest.json" ]; then
        if ! command -v npm >/dev/null 2>&1; then
            print_error "Node.js/npm is required to build frontend assets"
            echo "Please install Node.js 22+ and npm, then rerun this installer."
            exit 1
        fi

        print_info "Installing Node.js dependencies..."
        npm install --production=false --registry=https://registry.npmmirror.com

        print_info "Building frontend assets..."
        npm run prod
    fi
}

cleanup_install_state() {
    rm -f storage/installed bootstrap/cache/*.php
}

is_docker_registry_network_error() {
    local log_file="$1"

    grep -Eqi 'docker\.io|registry-1\.docker\.io|failed to resolve reference|failed to resolve source metadata|DeadlineExceeded|i/o timeout|connection reset by peer' "$log_file"
}

configure_docker_registry_mirror() {
    print_warn "Docker image pull failed, configuring Docker registry mirrors and retrying..."
    print_warn "This will restart Docker. Running containers on this server may be interrupted."

    local daemon_file="/etc/docker/daemon.json"
    local backup_file="${daemon_file}.bak.$(date +%Y%m%d%H%M%S)"
    local python_bin

    mkdir -p /etc/docker
    if [ -f "$daemon_file" ]; then
        cp "$daemon_file" "$backup_file"
        print_info "Docker daemon config backup: ${backup_file}"
    fi

    python_bin="$(command -v python3 2>/dev/null || command -v python 2>/dev/null || true)"
    if [ -z "$python_bin" ]; then
        print_error "Python is required to safely update ${daemon_file}"
        echo "Please configure Docker registry mirrors manually, then rerun this installer."
        return 1
    fi

    "$python_bin" - "$daemon_file" <<'PY'
import json
import os
import sys

path = sys.argv[1]
mirrors = [
    "https://docker.m.daocloud.io",
]

data = {}
if os.path.exists(path) and os.path.getsize(path) > 0:
    with open(path, "r", encoding="utf-8") as fp:
        data = json.load(fp)

current = data.get("registry-mirrors", [])
if not isinstance(current, list):
    current = []

for mirror in reversed(mirrors):
    if mirror not in current:
        current.insert(0, mirror)

data["registry-mirrors"] = current

with open(path, "w", encoding="utf-8") as fp:
    json.dump(data, fp, indent=2, ensure_ascii=False)
    fp.write("\n")
PY

    systemctl daemon-reload 2>/dev/null || true
    systemctl restart docker
    print_success "Docker registry mirrors configured"
}

print_docker_registry_mirror_help() {
    cat <<'HELP'
Please configure Docker registry mirrors manually, then rerun this installer:

mkdir -p /etc/docker
cat > /etc/docker/daemon.json <<'EOF'
{
  "registry-mirrors": [
    "https://docker.m.daocloud.io"
  ]
}
EOF
systemctl daemon-reload
systemctl restart docker
HELP
}

run_docker_with_mirror_retry() {
    local log_file
    local status

    log_file="$(mktemp)"

    set +e
    "$@" 2>&1 | tee "$log_file"
    status=${PIPESTATUS[0]}
    set -e

    if [ "$status" = "0" ]; then
        rm -f "$log_file"
        return 0
    fi

    if is_docker_registry_network_error "$log_file"; then
        rm -f "$log_file"
        if ! configure_docker_registry_mirror; then
            print_docker_registry_mirror_help
            return "$status"
        fi
        "$@"
        return
    fi

    rm -f "$log_file"
    return "$status"
}

# ============================================
# Environment Detection
# ============================================

detect_environment() {
    print_info "Detecting runtime environment..."

    local has_baota=0
    local has_docker=0
    local has_manual=0

    if [ -d "/www/server/panel" ]; then
        print_success "BT Panel environment detected"
        has_baota=1
    fi

    if command -v docker &> /dev/null; then
        if docker compose version &> /dev/null || command -v docker-compose &> /dev/null; then
            print_success "Docker environment detected"
            has_docker=1
        fi
    fi

    if command -v nginx &> /dev/null && command -v mysql &> /dev/null && command -v php &> /dev/null; then
        has_manual=1
    fi

    if [ "$has_baota" = "1" ] && [ "$has_docker" = "1" ]; then
        select_environment baota docker
        return
    fi

    if [ "$has_baota" = "1" ]; then
        set_environment "baota"
        return
    fi

    if [ "$has_docker" = "1" ]; then
        set_environment "docker"
        return
    fi

    if [ "$has_manual" = "1" ]; then
        print_success "Manual LNMP environment detected"
        set_environment "manual"
        return
    fi

    if [ "$has_baota" = "0" ] && [ "$has_docker" = "0" ] && [ "$has_manual" = "0" ]; then
        print_error "No supported runtime environment detected!"
        echo ""
        echo "Please install one of the following:"
        echo "  1. BT Panel: https://www.bt.cn"
        echo "  2. Docker: https://docs.docker.com"
        echo "  3. Manual: Nginx + MySQL + PHP"
        exit 1
    fi
}

set_environment() {
    ENV_TYPE="$1"

    case "$ENV_TYPE" in
        baota)
            if [ -f "${SCRIPT_DIR}/artisan" ]; then
                WEB_ROOT="${SCRIPT_DIR}"
            else
                WEB_ROOT="/www/wwwroot/${PROJECT_NAME}"
            fi
            detect_baota_services
            ;;
        docker)
            if [ -f "${SCRIPT_DIR}/artisan" ]; then
                WEB_ROOT="${SCRIPT_DIR}"
            else
                WEB_ROOT="$(pwd)/beikeshop"
            fi
            print_success "Using Docker environment"
            ;;
        manual)
            if [ -f "${SCRIPT_DIR}/artisan" ]; then
                WEB_ROOT="${SCRIPT_DIR}"
            else
                WEB_ROOT="/var/www/${PROJECT_NAME}"
            fi
            detect_manual_php_upstream
            print_success "Using Manual LNMP environment"
            ;;
    esac
}

environment_label() {
    case "$1" in
        baota)  echo "BT Panel" ;;
        docker) echo "Docker" ;;
        manual) echo "Manual LNMP" ;;
    esac
}

select_environment() {
    local available_envs=("$@")
    local choice
    local env

    echo ""
    echo "Select installation environment:"
    for i in "${!available_envs[@]}"; do
        env="${available_envs[$i]}"
        echo "  $((i + 1))) $(environment_label "$env")"
    done

    while true; do
        read -r -p "Enter choice [1-${#available_envs[@]}]: " choice
        if [[ "$choice" =~ ^[0-9]+$ ]] && [ "$choice" -ge 1 ] && [ "$choice" -le ${#available_envs[@]} ]; then
            set_environment "${available_envs[$((choice - 1))]}"
            return
        fi
        print_warn "Invalid choice, please try again."
    done
}

detect_baota_services() {
    if ! detect_bt_php_runtime; then
        print_error "No PHP installed in BT Panel!"
        exit 1
    fi
    print_success "BT PHP: ${BT_PHP_VERSION}"

    # Detect BT MySQL
    if [ -f "/www/server/mysql/bin/mysql" ]; then
        local mysql_ver=$(/www/server/mysql/bin/mysql --version | sed -E 's/.*([0-9]+\.[0-9]+\.[0-9]+).*/\1/')
        print_success "BT MySQL: $mysql_ver"
    fi

    # Detect BT Nginx/Apache
    if [ -f "/www/server/nginx/sbin/nginx" ]; then
        print_success "BT Nginx installed"
    elif [ -f "/www/server/apache/bin/httpd" ]; then
        print_success "BT Apache installed"
    fi
}

version_ge() {
    [ "$(printf '%s\n%s\n' "$2" "$1" | sort -V | head -n1)" = "$2" ]
}

precheck_baota_environment() {
    print_info "Checking BT Panel runtime requirements..."

    local missing_extensions=()
    local disabled_functions
    local required_functions=(putenv proc_open)
    local disabled_required_functions=()
    local failed=0

    if ! detect_bt_php_runtime; then
        print_error "BT PHP CLI not found"
        echo "Please install PHP 8.2 in BT Panel."
        exit 1
    fi

    if [ ! -f "/www/server/mysql/bin/mysql" ]; then
        print_error "BT MySQL not found"
        echo "Please install MySQL in BT Panel."
        failed=1
    fi

    if [ ! -f "/www/server/nginx/sbin/nginx" ] && [ ! -f "/www/server/apache/bin/httpd" ]; then
        print_error "BT Nginx or Apache not found"
        echo "Please install Nginx or Apache in BT Panel before running this installer."
        failed=1
    fi

    for extension in pdo_mysql mbstring gd zip bcmath fileinfo; do
        if ! "$BT_PHP_BIN" -m | grep -qi "^${extension}$"; then
            missing_extensions+=("$extension")
        fi
    done

    if [ ${#missing_extensions[@]} -gt 0 ]; then
        print_error "Missing PHP extensions: ${missing_extensions[*]}"
        echo "Please install/enable them in BT Panel: App Store -> PHP -> Install extensions."
        failed=1
    fi

    disabled_functions=$("$BT_PHP_BIN" -r 'echo ini_get("disable_functions");' 2>/dev/null || true)
    for function_name in "${required_functions[@]}"; do
        if echo ",${disabled_functions}," | grep -qi ",${function_name},"; then
            disabled_required_functions+=("$function_name")
        fi
    done

    if [ ${#disabled_required_functions[@]} -gt 0 ]; then
        print_error "Required PHP functions are disabled: ${disabled_required_functions[*]}"
        echo "Please remove them from disabled_functions in BT Panel PHP settings."
        failed=1
    fi

    if ! command -v composer >/dev/null 2>&1; then
        print_error "Composer not found"
        echo "Please install Composer 2.2 or later."
        failed=1
    else
        local composer_output
        local composer_status
        local composer_version

        composer_output=$(timeout 10 env COMPOSER_ALLOW_SUPERUSER=1 composer --no-plugins --no-scripts --version 2>&1)
        composer_status=$?
        composer_version=$(echo "$composer_output" | grep -Eo 'Composer version [0-9]+\.[0-9]+\.[0-9]+' | head -n1 | awk '{print $3}')

        if echo "$composer_output" | grep -qi "undefined function .*putenv"; then
            print_error "Composer cannot run because PHP function putenv() is disabled"
            echo "Please remove putenv from disabled_functions in BT Panel PHP settings, then upgrade Composer to 2.2+."
            failed=1
        elif [ "$composer_status" = "124" ]; then
            print_error "Composer version check timed out"
            echo "Please check whether Composer can run normally: COMPOSER_ALLOW_SUPERUSER=1 composer --no-plugins --no-scripts --version"
            failed=1
        elif [ "$composer_status" != "0" ]; then
            print_error "Composer cannot run correctly"
            echo "Please upgrade or reinstall Composer 2.2+."
            failed=1
        elif [ -z "$composer_version" ] || ! version_ge "$composer_version" "2.2.0"; then
            print_error "Composer 2.2+ is required. Current: ${composer_version:-unknown}"
            echo "Please run: composer self-update --2"
            failed=1
        fi
    fi

    if [ "$failed" = "1" ]; then
        exit 1
    fi

    print_success "BT Panel runtime requirements passed"
}

# ============================================
# Docker Installation
# ============================================

install_docker() {
    print_info "Starting Docker deployment..."

    # Create website directory
    if [ -f "${SCRIPT_DIR}/artisan" ]; then
        WEB_ROOT="${SCRIPT_DIR}"
        APP_CODE_PATH="."
    else
        WEB_ROOT="$(pwd)/beikeshop"
        APP_CODE_PATH="./beikeshop"
    fi
    mkdir -p "${WEB_ROOT}"
    print_info "Website root: ${WEB_ROOT}"

    if [ ! -f "docker-compose.yml" ] || [ ! -f "docker/nginx/Dockerfile" ]; then
        print_error "Local Docker configuration not found. Missing docker-compose.yml or docker/nginx/Dockerfile."
        exit 1
    fi

    if [ ! -f ".env" ]; then
        cp .env.example .env
    else
        local env_db_password
        local env_db_root_password

        env_db_password=$(read_env_value DB_PASSWORD .env)
        env_db_root_password=$(read_env_value DB_ROOT_PASSWORD .env)

        if [ -z "$USER_DB_PASSWORD" ] && [ -n "$env_db_password" ]; then
            DB_PASSWORD="$env_db_password"
        fi

        if [ -z "$USER_DB_ROOT_PASSWORD" ] && [ -n "$env_db_root_password" ]; then
            DB_ROOT_PASSWORD="$env_db_root_password"
        fi
    fi

    # Update .env with user configuration
    print_info "Configuring environment variables..."
    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i.bak "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_HOST=.*|DB_HOST=mysql|;s|DB_PORT=.*|DB_PORT=3306|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|;s|DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}|" .env
        rm -f .env.bak
    else
        sed -i "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_HOST=.*|DB_HOST=mysql|;s|DB_PORT=.*|DB_PORT=3306|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|;s|DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}|" .env
    fi

    if grep -q '^APP_CODE_PATH=' .env; then
        sed -i "s|APP_CODE_PATH=.*|APP_CODE_PATH=${APP_CODE_PATH}|" .env
    else
        echo "APP_CODE_PATH=${APP_CODE_PATH}" >> .env
    fi

    if grep -q '^DB_ROOT_PASSWORD=' .env; then
        sed -i "s|DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}|" .env
    else
        echo "DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}" >> .env
    fi

    if ! grep -q '^MYSQL_IMAGE=' .env; then
        echo "MYSQL_IMAGE=mysql:8.0" >> .env
    fi

    # Start Docker containers
    print_info "Starting Docker containers..."
    run_docker_with_mirror_retry docker compose up -d mysql
    run_docker_with_mirror_retry docker compose --profile nginx up -d --build

    # Wait for MySQL
    print_info "Waiting for database to be ready..."
    for i in $(seq 1 30); do
        if docker compose exec -T -e MYSQL_PWD="${DB_ROOT_PASSWORD}" mysql mysqladmin ping -h127.0.0.1 -uroot --silent >/dev/null 2>&1; then
            break
        fi
        sleep 2
    done

    # Keep MySQL user password in sync when containers are reused.
    print_info "Syncing database user..."
    docker compose exec -T -e MYSQL_PWD="${DB_ROOT_PASSWORD}" mysql mysql -h127.0.0.1 -uroot << EOF
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASSWORD}';
ALTER USER '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';
FLUSH PRIVILEGES;
EOF

    print_info "Waiting for application database connection..."
    db_ready=0
    for i in $(seq 1 30); do
        if docker compose exec -T \
            -e DB_HOST=mysql \
            -e DB_PORT=3306 \
            -e DB_DATABASE="${DB_NAME}" \
            -e DB_USERNAME="${DB_USER}" \
            -e DB_PASSWORD="${DB_PASSWORD}" \
            nginx php -r '$dsn = "mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT").";dbname=".getenv("DB_DATABASE"); try { new PDO($dsn, getenv("DB_USERNAME"), getenv("DB_PASSWORD")); exit(0); } catch (Throwable $e) { exit(1); }' >/dev/null 2>&1; then
            db_ready=1
            break
        fi
        sleep 2
    done

    if [ "$db_ready" != "1" ]; then
        print_error "Database is not reachable from application container"
        exit 1
    fi

    # Install Composer dependencies inside the PHP container
    if [ ! -d "${WEB_ROOT}/vendor" ]; then
        print_info "Installing Composer dependencies..."
        docker compose exec -T nginx env COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-scripts --optimize-autoloader
    fi

    if [ ! -f "${WEB_ROOT}/public/mix-manifest.json" ] && [ -f "${WEB_ROOT}/package.json" ]; then
        print_info "Building frontend assets..."
        if [ -f "${WEB_ROOT}/package-lock.json" ]; then
            docker compose exec -T nginx npm ci --registry=https://registry.npmmirror.com
        else
            docker compose exec -T nginx npm install --production=false --registry=https://registry.npmmirror.com
        fi
        docker compose exec -T nginx npm run prod
        docker compose exec -T nginx sh -lc 'chown -R www-data:www-data public storage bootstrap/cache && chmod -R ug+rwX public storage bootstrap/cache'
    fi

    docker compose exec -T nginx sh -lc "$(declare -f cleanup_install_state); cleanup_install_state"

    # Execute artisan installation
    print_info "Running application installation..."
    docker compose exec -T nginx php artisan beikeshop:install \
        --domain="${DOMAIN}" \
        --db-host=mysql \
        --db-name="${DB_NAME}" \
        --db-user="${DB_USER}" \
        --db-password="${DB_PASSWORD}" \
        --admin-email="${ADMIN_EMAIL}" \
        --admin-password="${ADMIN_PASSWORD}" \
        --force

    print_success "Docker deployment completed!"
    echo ""
    print_info "Website: http://${DOMAIN}"
    print_info "Admin:   http://${DOMAIN}/admin"
}

# ============================================
# BT Panel Installation
# ============================================

install_baota() {
    print_info "Starting BT Panel deployment..."
    precheck_baota_environment

    # Create website directory
    mkdir -p "${WEB_ROOT}"
    print_info "Website root: ${WEB_ROOT}"
    resolve_baota_database_names
    precheck_baota_install_target

    if ! bt_api_create_site; then
        print_error "BT Panel site creation failed. Installation stopped to avoid creating inconsistent manual fallback resources."
        exit 1
    fi

    # Download BeikeShop
    download_beikeshop "${WEB_ROOT}"

    # Configure .env
    print_info "Configuring environment variables..."
    cd "${WEB_ROOT}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
    fi

    # Update .env
    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i.bak "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
        rm -f .env.bak
    else
        sed -i "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
    fi

    cleanup_install_state
    install_project_dependencies

    # Generate APP_KEY
    cleanup_install_state
    php artisan key:generate --force

    # Set permissions
    print_info "Setting directory permissions..."
    chown -R www:www "${WEB_ROOT}" 2>/dev/null || print_warn "Some files could not be chowned to www:www, continuing."
    chmod -R 755 "${WEB_ROOT}" 2>/dev/null || print_warn "Some files could not be chmod 755, continuing."
    chmod -R 775 "${WEB_ROOT}/storage" "${WEB_ROOT}/bootstrap/cache" 2>/dev/null || print_warn "Some writable directories could not be chmod 775, continuing."

    # Set website root to public/
    print_info "Website root: ${WEB_ROOT}/public"

    # Execute artisan installation
    print_info "Running application installation..."
    cleanup_install_state
    php artisan beikeshop:install \
        --domain="${DOMAIN}" \
        --db-host=127.0.0.1 \
        --db-name="${DB_NAME}" \
        --db-user="${DB_USER}" \
        --db-password="${DB_PASSWORD}" \
        --admin-email="${ADMIN_EMAIL}" \
        --admin-password="${ADMIN_PASSWORD}" \
        --force

    print_success "BT Panel deployment completed!"
    echo ""
    print_info "Website: http://${DOMAIN}"
    print_info "Admin:   http://${DOMAIN}/admin"
}

# ============================================
# Manual LNMP Installation
# ============================================

install_manual() {
    print_info "Starting manual LNMP deployment..."

    # Create website directory
    sudo mkdir -p "${WEB_ROOT}"
    print_info "Website root: ${WEB_ROOT}"

    # Download BeikeShop
    download_beikeshop "${WEB_ROOT}"

    # Create database
    print_info "Creating database..."
    run_mysql_as_root mysql << EOF
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD}';
ALTER USER '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
EOF
    print_success "Database ${DB_NAME} created"

    # Configure .env
    print_info "Configuring environment variables..."
    cd "${WEB_ROOT}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
    fi

    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i.bak "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
        rm -f .env.bak
    else
        sed -i "s|APP_URL=.*|APP_URL=http://${DOMAIN}|;s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|;s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|;s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
    fi

    cleanup_install_state
    install_project_dependencies

    cleanup_install_state
    php artisan key:generate --force

    # Set permissions
    print_info "Setting directory permissions..."
    sudo chown -R www-data:www-data "${WEB_ROOT}"
    sudo chmod -R 755 "${WEB_ROOT}"
    sudo chmod -R 775 "${WEB_ROOT}/storage" "${WEB_ROOT}/bootstrap/cache"

    # Set website root to public/
    print_info "Website root: ${WEB_ROOT}/public"

    # Configure Nginx
    print_info "Configuring Nginx virtual host..."
    local nginx_conf="/etc/nginx/sites-available/${PROJECT_NAME}"

    detect_manual_php_upstream
    cat > "$nginx_conf" << NGINX
server {
    listen 80;
    server_name ${DOMAIN};
    root ${WEB_ROOT}/public;
    index index.php index.html;

    client_max_body_size 100M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass ${PHP_UPSTREAM};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
NGINX

    # Enable site
    sudo ln -sf "$nginx_conf" /etc/nginx/sites-enabled/
    sudo nginx -t && sudo systemctl reload nginx

    # Execute artisan installation
    print_info "Running application installation..."
    cleanup_install_state
    php artisan beikeshop:install \
        --domain="${DOMAIN}" \
        --db-host=127.0.0.1 \
        --db-name="${DB_NAME}" \
        --db-user="${DB_USER}" \
        --db-password="${DB_PASSWORD}" \
        --admin-email="${ADMIN_EMAIL}" \
        --admin-password="${ADMIN_PASSWORD}" \
        --force

    print_success "Manual LNMP deployment completed!"
    echo ""
    print_info "Website: http://${DOMAIN}"
    print_info "Admin:   http://${DOMAIN}/admin"
}

# ============================================
# Download BeikeShop
# ============================================

download_beikeshop() {
    local target_dir="$1"

    if [ -f "${target_dir}/artisan" ]; then
        print_success "BeikeShop source already exists, skipping download"
        # Detect if it's source mode (has package.json but no compiled assets)
        if [ -f "${target_dir}/package.json" ] && [ ! -d "${target_dir}/vendor" ]; then
            INSTALL_MODE="source"
            print_info "Detected source installation (needs composer + npm build)"
        fi
        return
    fi

    # Ask user for installation mode
    echo ""
    echo "Select Installation Mode:"
    echo "  1) Release Package (pre-built, recommended)"
    echo "  2) Source Code (git clone, needs build)"
    echo "  3) GitHub Clone (https://github.com/beikeshop/beikeshop.git)"
    read -p "Enter choice [1-3] (default: 1): " mode_choice
    case "$mode_choice" in
        2|source)
            INSTALL_MODE="source"
            download_from_source "$target_dir"
            ;;
        3|github)
            INSTALL_MODE="source"
            download_from_source "$target_dir" "https://github.com/beikeshop/beikeshop.git"
            ;;
        *)
            INSTALL_MODE="release"
            download_from_release "$target_dir"
            ;;
    esac
}

download_from_release() {
    local target_dir="$1"
    print_info "Downloading BeikeShop v${BEIKESHOP_VERSION} (release package)..."

    local tmp_file="/tmp/beikeshop-${BEIKESHOP_VERSION}.zip"

    if [ ! -f "$tmp_file" ]; then
        curl -L -o "$tmp_file" "${BEIKESHOP_URL}" || {
            print_error "Download failed! Please download manually and extract to ${target_dir}"
            exit 1
        }
    fi

    print_info "Extracting to ${target_dir}..."
    local tmp_dir="/tmp/beikeshop-extract-$$"
    mkdir -p "$tmp_dir"
    unzip -q "$tmp_file" -d "$tmp_dir"

    # Find source directory (may be in subdirectory)
    local src_dir=$(find "$tmp_dir" -name "artisan" -type f -exec dirname {} \; | head -1)
    if [ -z "$src_dir" ]; then
        print_error "Extraction failed: artisan file not found"
        exit 1
    fi

    # Copy to target directory
    cp -r "${src_dir}/." "${target_dir}/"
    rm -rf "$tmp_dir"

    print_success "BeikeShop release package deployed"
}

download_from_source() {
    local target_dir="$1"
    local repo_url="${2:-$BEIKESHOP_REPO}"
    print_info "Cloning from ${repo_url}..."

    if command -v git &> /dev/null; then
        git clone --depth 1 "${repo_url}" "$target_dir" || {
            print_error "Git clone failed!"
            exit 1
        }
        print_success "BeikeShop cloned successfully"
    else
        print_error "Git is not installed! Please install git first."
        exit 1
    fi
}

# ============================================
# Display Installation Result
# ============================================

show_result() {
    echo ""
    echo -e "${GREEN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║         Installation Successful!         ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "  Environment: ${BLUE}${ENV_TYPE}${NC}"
    echo -e "  Web Root:    ${BLUE}${WEB_ROOT}${NC}"
    echo -e "  URL:         ${BLUE}http://${DOMAIN}${NC}"
    echo -e "  Admin URL:   ${BLUE}http://${DOMAIN}/admin${NC}"
    echo ""
    echo -e "  Admin Email: ${BLUE}${ADMIN_EMAIL}${NC}"
    echo -e "  Admin Pass:  ${BLUE}${ADMIN_PASSWORD}${NC}"
    echo ""
    echo -e "  DB Name:     ${BLUE}${DB_NAME}${NC}"
    echo -e "  DB User:     ${BLUE}${DB_USER}${NC}"
    echo -e "  DB Password: ${BLUE}${DB_PASSWORD}${NC}"
    echo ""
    echo -e "${YELLOW}⚠️  Security: Change admin password immediately in production!${NC}"
    echo ""
}

# ============================================
# Main
# ============================================

main() {
    print_banner

    # Detect environment
    detect_environment

    # Configure website domain
    configure_domain

    # Execute installation based on environment
    case "$ENV_TYPE" in
        docker)  install_docker  ;;
        baota)   install_baota   ;;
        manual)  install_manual  ;;
        *)
            print_error "Unknown environment type: $ENV_TYPE"
            exit 1
            ;;
    esac

    # Show result
    show_result
}

# Execute main flow
main "$@"
