# Backup e Sincronização de VMs

Este documento descreve a configuração de backup automático e sincronização entre as VMs do **wks** (desktop) e **notebook**.

## Arquitetura

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              Rede Local                                      │
│                                                                              │
│  ┌─────────────────────────┐              ┌─────────────────────────┐       │
│  │  HOST: wks.local        │              │  HOST: notebook.local   │       │
│  │  (Desktop)              │◄────────────►│  (Notebook)             │       │
│  │                         │   Sync via   │                         │       │
│  │  ┌───────────────────┐  │   mDNS/Avahi │  ┌───────────────────┐  │       │
│  │  │ virbr0            │  │              │  │ virbr0            │  │       │
│  │  │ 192.168.122.1     │  │              │  │ 192.168.122.1     │  │       │
│  │  │                   │  │              │  │                   │  │       │
│  │  │  ┌─────────────┐  │  │              │  │  ┌─────────────┐  │  │       │
│  │  │  │ VM: ol9.4   │  │  │              │  │  │ VM: ol9.4   │  │  │       │
│  │  │  │ .122.100    │  │  │              │  │  │ .122.100    │  │  │       │
│  │  │  │             │  │  │              │  │  │             │  │  │       │
│  │  │  │ /var/www    │  │  │              │  │  │ /var/www    │  │  │       │
│  │  │  │ /backup     │  │  │              │  │  │ /backup     │  │  │       │
│  │  │  └─────────────┘  │  │              │  │  └─────────────┘  │  │       │
│  │  └───────────────────┘  │              │  └───────────────────┘  │       │
│  └─────────────────────────┘              └─────────────────────────┘       │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Parte 1: Configuração de Backup na VM

> **Executar esta seção em cada VM (wks e notebook)**

### 1.1 Pré-requisitos

-   VM Oracle Linux 9.x com KVM/libvirt
-   Disco separado montado em `/var/www` (opcional, mas recomendado)
-   Diretório `/backup` para armazenamento

### 1.2 Criar Diretório de Backup

```bash
# Acessar a VM
ssh opc@192.168.122.100

# Criar diretório de backup
sudo mkdir -p /backup/{files,databases}
sudo chown -R opc:opc /backup
```

### 1.3 Instalar Script de Backup

Criar o arquivo `/usr/local/bin/backup-projetos.sh`:

```bash
sudo tee /usr/local/bin/backup-projetos.sh > /dev/null << 'EOF'
#!/bin/bash
# =============================================================================
# Backup Automático de Projetos Web
# Executa diariamente via cron
# =============================================================================

BACKUP_DIR="/backup"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=7

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

log() {
    echo -e "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# -----------------------------------------------------------------------------
# 1. Backup dos arquivos do /var/www
# -----------------------------------------------------------------------------
log "${GREEN}Iniciando backup de arquivos...${NC}"

tar -czf "$BACKUP_DIR/files/www_${TIMESTAMP}.tar.gz" \
    --exclude='*/node_modules' \
    --exclude='*/vendor' \
    --exclude='*/.git' \
    --exclude='*/storage/logs/*' \
    --exclude='*/storage/framework/cache/*' \
    --exclude='*/storage/framework/sessions/*' \
    --exclude='*/storage/framework/views/*' \
    -C /var www 2>/dev/null

if [ $? -eq 0 ]; then
    log "${GREEN}✓ Backup de arquivos criado: www_${TIMESTAMP}.tar.gz${NC}"
else
    log "${RED}✗ Erro no backup de arquivos${NC}"
fi

# -----------------------------------------------------------------------------
# 2. Backup dos bancos de dados
# -----------------------------------------------------------------------------
log "${GREEN}Iniciando backup de bancos de dados...${NC}"

MYSQL_USER="root"
MYSQL_PASS="caluma"

# Lista de bancos para backup (excluir bancos do sistema)
DATABASES=$(mysql -u $MYSQL_USER -p"$MYSQL_PASS" -e "SHOW DATABASES;" 2>/dev/null | \
    grep -Ev "(Database|information_schema|performance_schema|mysql|sys)")

for DB in $DATABASES; do
    log "  Exportando banco: $DB"
    mysqldump -u $MYSQL_USER -p"$MYSQL_PASS" \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        "$DB" 2>/dev/null | gzip > "$BACKUP_DIR/databases/${DB}_${TIMESTAMP}.sql.gz"

    if [ $? -eq 0 ]; then
        SIZE=$(du -h "$BACKUP_DIR/databases/${DB}_${TIMESTAMP}.sql.gz" | cut -f1)
        log "${GREEN}  ✓ $DB exportado ($SIZE)${NC}"
    else
        log "${RED}  ✗ Erro ao exportar $DB${NC}"
    fi
done

# -----------------------------------------------------------------------------
# 3. Limpeza de backups antigos
# -----------------------------------------------------------------------------
log "Removendo backups com mais de $RETENTION_DAYS dias..."

find "$BACKUP_DIR/files" -name "*.tar.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR/databases" -name "*.sql.gz" -mtime +$RETENTION_DAYS -delete

# -----------------------------------------------------------------------------
# 4. Resumo
# -----------------------------------------------------------------------------
log ""
log "============================================="
log "Backup concluído!"
log "Arquivos: $(du -sh $BACKUP_DIR/files 2>/dev/null | cut -f1)"
log "Bancos:   $(du -sh $BACKUP_DIR/databases 2>/dev/null | cut -f1)"
log "============================================="
EOF

sudo chmod +x /usr/local/bin/backup-projetos.sh
```

### 1.4 Instalar Script de Monitoramento

Criar o arquivo `/usr/local/bin/backup-monitor.sh`:

```bash
sudo tee /usr/local/bin/backup-monitor.sh > /dev/null << 'EOF'
#!/bin/bash
# =============================================================================
# Monitoramento de Backup com Alertas
# =============================================================================

BACKUP_DIR="/backup"
MAX_AGE_HOURS=26
MIN_FILES_SIZE=1000000
LOG_FILE="/var/log/backup-monitor.log"
ALERT_FILE="/tmp/backup_alert"
HOSTNAME=$(hostname)

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE
}

send_alert() {
    local message="$1"
    log "⚠️  ALERTA: $message"
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $message" >> $ALERT_FILE
}

check_ok() {
    log "✅ $1"
}

log "=== Iniciando verificação de backups ==="

# Verificar backup de arquivos
LATEST_FILES=$(find $BACKUP_DIR/files -name "*.tar.gz" -mmin -$((MAX_AGE_HOURS * 60)) 2>/dev/null | head -1)

if [ -z "$LATEST_FILES" ]; then
    send_alert "Backup de arquivos não encontrado ou muito antigo (>$MAX_AGE_HOURS horas)"
else
    SIZE=$(stat -c%s "$LATEST_FILES" 2>/dev/null || echo 0)
    if [ "$SIZE" -lt "$MIN_FILES_SIZE" ]; then
        send_alert "Backup de arquivos muito pequeno: $(du -h $LATEST_FILES | cut -f1)"
    else
        check_ok "Backup de arquivos OK: $(basename $LATEST_FILES) ($(du -h $LATEST_FILES | cut -f1))"
    fi
fi

# Verificar backups de banco
DB_COUNT=0
DB_MISSING=0

for expected_db in spire_26 spire_next spire_prod_new ges-new; do
    LATEST_DB=$(find $BACKUP_DIR/databases -name "${expected_db}_*.sql.gz" -mmin -$((MAX_AGE_HOURS * 60)) 2>/dev/null | head -1)

    if [ -z "$LATEST_DB" ]; then
        send_alert "Backup do banco '$expected_db' não encontrado ou muito antigo"
        ((DB_MISSING++))
    else
        SIZE=$(stat -c%s "$LATEST_DB" 2>/dev/null || echo 0)
        if [ "$SIZE" -lt 1000 ]; then
            send_alert "Backup do banco '$expected_db' muito pequeno"
            ((DB_MISSING++))
        else
            check_ok "Backup do banco '$expected_db' OK: $(du -h $LATEST_DB | cut -f1)"
            ((DB_COUNT++))
        fi
    fi
done

# Verificar espaço em disco
DISK_USAGE=$(df /backup 2>/dev/null | tail -1 | awk '{print $5}' | tr -d '%')

if [ -n "$DISK_USAGE" ] && [ "$DISK_USAGE" -gt 85 ]; then
    send_alert "Espaço em disco de backup crítico: ${DISK_USAGE}% usado"
else
    check_ok "Espaço em disco OK: ${DISK_USAGE:-N/A}% usado"
fi

# Resumo
log ""
log "=== Resumo ==="
log "Bancos verificados: $DB_COUNT OK, $DB_MISSING com problema"

if [ "$DB_MISSING" -eq 0 ] && [ -n "$LATEST_FILES" ]; then
    log "✅ Todos os backups estão OK!"
    [ -f "$ALERT_FILE" ] && rm -f "$ALERT_FILE"
    exit 0
else
    log "❌ Há problemas nos backups"
    exit 1
fi
EOF

sudo chmod +x /usr/local/bin/backup-monitor.sh
```

### 1.5 Configurar Cron (na VM)

```bash
# Adicionar ao cron do root
sudo crontab -e
```

Adicionar as linhas:

```cron
# Backup diário às 2 AM
0 2 * * * /usr/local/bin/backup-projetos.sh >> /var/log/backup.log 2>&1

# Monitoramento às 3 AM (1 hora após backup)
0 3 * * * /usr/local/bin/backup-monitor.sh >> /var/log/backup-monitor.log 2>&1
```

### 1.6 Testar Backup

```bash
# Executar backup manualmente
sudo /usr/local/bin/backup-projetos.sh

# Verificar resultado
sudo /usr/local/bin/backup-monitor.sh

# Listar backups
ls -lh /backup/files/
ls -lh /backup/databases/
```

---

## Parte 2: Snapshot de VM no Host

> **Executar esta seção em cada HOST (wks e notebook)**

### 2.1 Criar Script de Snapshot

Criar `/usr/local/bin/snapshot-vm.sh` no host:

```bash
sudo tee /usr/local/bin/snapshot-vm.sh > /dev/null << 'EOF'
#!/bin/bash
# =============================================================================
# Snapshot do Disco de Dados da VM
# =============================================================================

VM_NAME="ol9.4"
DISK_PATH="/var/lib/libvirt/images/ol9.4-www.qcow2"
SNAPSHOT_DIR="/var/lib/libvirt/snapshots"
RETENTION_DAYS=7
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

mkdir -p "$SNAPSHOT_DIR"

log "Iniciando snapshot do disco de dados da VM $VM_NAME..."

# Verificar se VM está rodando
VM_STATE=$(virsh domstate $VM_NAME 2>/dev/null)

if [ "$VM_STATE" = "running" ]; then
    log "VM está rodando, criando snapshot quiesce..."

    # Tentar snapshot com quiesce (requer qemu-guest-agent)
    virsh snapshot-create-as $VM_NAME \
        --name "snap_${TIMESTAMP}" \
        --description "Snapshot automático" \
        --disk-only \
        --quiesce 2>/dev/null

    if [ $? -ne 0 ]; then
        log "Quiesce falhou, fazendo snapshot simples..."
        # Fallback: cópia direta do disco
        cp --sparse=always "$DISK_PATH" "$SNAPSHOT_DIR/www_snapshot_${TIMESTAMP}.qcow2"
    fi
else
    log "VM parada, copiando disco diretamente..."
    cp --sparse=always "$DISK_PATH" "$SNAPSHOT_DIR/www_snapshot_${TIMESTAMP}.qcow2"
fi

if [ -f "$SNAPSHOT_DIR/www_snapshot_${TIMESTAMP}.qcow2" ]; then
    log "✅ Snapshot criado: $SNAPSHOT_DIR/www_snapshot_${TIMESTAMP}.qcow2 ($(du -h $SNAPSHOT_DIR/www_snapshot_${TIMESTAMP}.qcow2 | cut -f1))"
fi

# Limpeza
log "Limpando snapshots com mais de $RETENTION_DAYS dias..."
DELETED=$(find "$SNAPSHOT_DIR" -name "www_snapshot_*.qcow2" -mtime +$RETENTION_DAYS -delete -print | wc -l)
log "Removidos $DELETED snapshots antigos"

log ""
log "=== Snapshots Existentes ==="
ls -lh "$SNAPSHOT_DIR"/*.qcow2 2>/dev/null | while read line; do
    log "  $line"
done
log "Espaço total usado: $(du -sh $SNAPSHOT_DIR 2>/dev/null | cut -f1)"
log "============================================="
EOF

sudo chmod +x /usr/local/bin/snapshot-vm.sh
```

### 2.2 Criar Diretório de Snapshots

```bash
sudo mkdir -p /var/lib/libvirt/snapshots
```

### 2.3 Configurar Timer Systemd

Criar service:

```bash
sudo tee /etc/systemd/system/vm-snapshot.service > /dev/null << 'EOF'
[Unit]
Description=Snapshot da VM ol9.4 (disco www)
After=network.target libvirtd.service

[Service]
Type=oneshot
ExecStart=/usr/local/bin/snapshot-vm.sh
StandardOutput=journal
StandardError=journal
EOF
```

Criar timer:

```bash
sudo tee /etc/systemd/system/vm-snapshot.timer > /dev/null << 'EOF'
[Unit]
Description=Timer diário para snapshot da VM ol9.4

[Timer]
OnCalendar=*-*-* 04:00:00
Persistent=true
RandomizedDelaySec=5min

[Install]
WantedBy=timers.target
EOF
```

Ativar:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now vm-snapshot.timer

# Verificar
systemctl status vm-snapshot.timer
```

### 2.4 Testar Snapshot

```bash
sudo /usr/local/bin/snapshot-vm.sh
ls -lh /var/lib/libvirt/snapshots/
```

---

## Parte 3: Configuração de Sincronização

> **Executar esta seção em AMBOS os hosts (wks e notebook)**

### 3.1 Verificar/Instalar Avahi

```bash
# Verificar se Avahi está instalado e ativo
systemctl status avahi-daemon

# Se não estiver instalado:
# Arch/CachyOS:
sudo pacman -S avahi nss-mdns

# Fedora/RHEL:
sudo dnf install avahi nss-mdns

# Ubuntu/Debian:
sudo apt install avahi-daemon avahi-utils libnss-mdns

# Ativar
sudo systemctl enable --now avahi-daemon
```

### 3.2 Configurar NSS para mDNS

Editar `/etc/nsswitch.conf`:

```bash
sudo nano /etc/nsswitch.conf
```

Modificar a linha `hosts:` para incluir `mdns_minimal`:

```
hosts: mymachines mdns_minimal [NOTFOUND=return] resolve [!UNAVAIL=return] files dns
```

### 3.3 Verificar Hostname

```bash
# Ver hostname atual
hostnamectl

# Definir hostname (se necessário)
sudo hostnamectl set-hostname wks        # No desktop
sudo hostnamectl set-hostname notebook   # No notebook
```

### 3.4 Testar Resolução mDNS

```bash
# Do wks, testar resolução do notebook:
ping notebook.local

# Do notebook, testar resolução do wks:
ping wks.local

# Ou usar avahi-resolve:
avahi-resolve -n wks.local
avahi-resolve -n notebook.local
```

### 3.5 Configurar SSH sem Senha

Em cada máquina, gerar chave SSH (se ainda não tiver):

```bash
# Gerar chave (aceitar defaults)
ssh-keygen -t ed25519

# Do wks, copiar chave para notebook:
ssh-copy-id ldo@notebook.local

# Do notebook, copiar chave para wks:
ssh-copy-id ldo@wks.local

# Testar conexão sem senha:
ssh ldo@notebook.local "echo 'Conexão OK!'"
```

### 3.6 Instalar Script de Sincronização

Criar `/usr/local/bin/sync-vm.sh` em ambos os hosts:

```bash
sudo tee /usr/local/bin/sync-vm.sh > /dev/null << 'EOF'
#!/bin/bash
# =============================================================================
# Sincronização de VM entre hosts
# Uso: sync-vm.sh [push|pull] [host_destino]
# =============================================================================

set -e

# Configuração
VM_NAME="ol9.4"
VM_DISK_WWW="/var/lib/libvirt/images/ol9.4-www.qcow2"
VM_DISK_SYS="/var/lib/libvirt/images/ol9.4-1_sparse.qcow2"
REMOTE_USER="ldo"
LOG_FILE="/var/log/vm-sync.log"

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() {
    echo -e "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

usage() {
    echo "Uso: $0 [push|pull] [host_destino]"
    echo ""
    echo "Exemplos:"
    echo "  $0 push notebook.local   # Enviar VM para notebook"
    echo "  $0 pull wks.local        # Baixar VM do wks"
    echo ""
    echo "Opções:"
    echo "  push   Enviar discos da VM local para host remoto"
    echo "  pull   Baixar discos da VM do host remoto"
    echo "  --data-only   Sincronizar apenas disco de dados (www)"
    exit 1
}

check_vm_stopped() {
    local host=$1
    if [ "$host" = "local" ]; then
        VM_STATE=$(virsh domstate $VM_NAME 2>/dev/null || echo "não encontrada")
    else
        VM_STATE=$(ssh ${REMOTE_USER}@${host} "virsh domstate $VM_NAME 2>/dev/null" || echo "não encontrada")
    fi

    if [ "$VM_STATE" = "running" ]; then
        return 1
    fi
    return 0
}

stop_vm() {
    local host=$1
    log "${YELLOW}Parando VM $VM_NAME em $host...${NC}"

    if [ "$host" = "local" ]; then
        virsh shutdown $VM_NAME 2>/dev/null || true
        sleep 5
        virsh destroy $VM_NAME 2>/dev/null || true
    else
        ssh ${REMOTE_USER}@${host} "virsh shutdown $VM_NAME 2>/dev/null || true; sleep 5; virsh destroy $VM_NAME 2>/dev/null || true"
    fi
}

start_vm() {
    local host=$1
    log "${GREEN}Iniciando VM $VM_NAME em $host...${NC}"

    if [ "$host" = "local" ]; then
        virsh start $VM_NAME
    else
        ssh ${REMOTE_USER}@${host} "virsh start $VM_NAME"
    fi
}

# Processar argumentos
ACTION=""
REMOTE_HOST=""
DATA_ONLY=false

while [[ $# -gt 0 ]]; do
    case $1 in
        push|pull)
            ACTION="$1"
            shift
            ;;
        --data-only)
            DATA_ONLY=true
            shift
            ;;
        *)
            REMOTE_HOST="$1"
            shift
            ;;
    esac
done

if [ -z "$ACTION" ] || [ -z "$REMOTE_HOST" ]; then
    usage
fi

# Verificar conectividade
log "Verificando conexão com $REMOTE_HOST..."
if ! ping -c 1 -W 2 "$REMOTE_HOST" > /dev/null 2>&1; then
    log "${RED}❌ Host $REMOTE_HOST não está acessível${NC}"
    exit 1
fi

if ! ssh -o ConnectTimeout=5 ${REMOTE_USER}@${REMOTE_HOST} "echo OK" > /dev/null 2>&1; then
    log "${RED}❌ SSH para $REMOTE_HOST falhou${NC}"
    exit 1
fi

log "${GREEN}✓ Conexão com $REMOTE_HOST OK${NC}"

# Executar sincronização
case $ACTION in
    push)
        log "=== PUSH: Enviando VM para $REMOTE_HOST ==="

        # Parar VM local
        if ! check_vm_stopped "local"; then
            stop_vm "local"
        fi

        # Parar VM remota (se existir)
        ssh ${REMOTE_USER}@${REMOTE_HOST} "virsh destroy $VM_NAME 2>/dev/null || true"

        # Sincronizar disco de dados
        log "Sincronizando disco de dados (www)..."
        rsync -avP --sparse "$VM_DISK_WWW" ${REMOTE_USER}@${REMOTE_HOST}:"$VM_DISK_WWW"

        # Sincronizar disco de sistema (se não for data-only)
        if [ "$DATA_ONLY" = false ]; then
            log "Sincronizando disco de sistema..."
            rsync -avP --sparse "$VM_DISK_SYS" ${REMOTE_USER}@${REMOTE_HOST}:"$VM_DISK_SYS"

            # Exportar e importar definição da VM
            log "Atualizando definição da VM..."
            virsh dumpxml $VM_NAME > /tmp/${VM_NAME}.xml
            scp /tmp/${VM_NAME}.xml ${REMOTE_USER}@${REMOTE_HOST}:/tmp/
            ssh ${REMOTE_USER}@${REMOTE_HOST} "virsh define /tmp/${VM_NAME}.xml"
        fi

        log "${GREEN}✅ Push concluído!${NC}"

        # Perguntar se quer iniciar VMs
        read -p "Iniciar VM local? [y/N] " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            start_vm "local"
        fi

        read -p "Iniciar VM remota? [y/N] " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            start_vm "$REMOTE_HOST"
        fi
        ;;

    pull)
        log "=== PULL: Baixando VM de $REMOTE_HOST ==="

        # Parar VM local
        if ! check_vm_stopped "local"; then
            stop_vm "local"
        fi

        # Verificar/parar VM remota
        ssh ${REMOTE_USER}@${REMOTE_HOST} "virsh shutdown $VM_NAME 2>/dev/null || true; sleep 3"

        # Sincronizar disco de dados
        log "Sincronizando disco de dados (www)..."
        rsync -avP --sparse ${REMOTE_USER}@${REMOTE_HOST}:"$VM_DISK_WWW" "$VM_DISK_WWW"

        # Sincronizar disco de sistema (se não for data-only)
        if [ "$DATA_ONLY" = false ]; then
            log "Sincronizando disco de sistema..."
            rsync -avP --sparse ${REMOTE_USER}@${REMOTE_HOST}:"$VM_DISK_SYS" "$VM_DISK_SYS"

            # Importar definição da VM
            log "Atualizando definição da VM..."
            scp ${REMOTE_USER}@${REMOTE_HOST}:/tmp/${VM_NAME}.xml /tmp/ 2>/dev/null || \
                ssh ${REMOTE_USER}@${REMOTE_HOST} "virsh dumpxml $VM_NAME" > /tmp/${VM_NAME}.xml
            virsh define /tmp/${VM_NAME}.xml
        fi

        log "${GREEN}✅ Pull concluído!${NC}"

        read -p "Iniciar VM local? [y/N] " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            start_vm "local"
        fi
        ;;
esac

log "Sincronização finalizada!"
EOF

sudo chmod +x /usr/local/bin/sync-vm.sh
```

---

## Parte 4: Uso

### 4.1 Comandos de Sincronização

```bash
# Do wks, enviar VM para notebook:
sudo sync-vm.sh push notebook.local

# Do wks, enviar apenas disco de dados:
sudo sync-vm.sh push notebook.local --data-only

# Do notebook, baixar VM do wks:
sudo sync-vm.sh pull wks.local

# Do notebook, baixar apenas dados:
sudo sync-vm.sh pull wks.local --data-only
```

### 4.2 Comandos de Backup

```bash
# Na VM: executar backup manual
ssh opc@192.168.122.100 sudo /usr/local/bin/backup-projetos.sh

# Na VM: verificar status dos backups
ssh opc@192.168.122.100 sudo /usr/local/bin/backup-monitor.sh

# No host: executar snapshot manual
sudo /usr/local/bin/snapshot-vm.sh

# No host: verificar timer de snapshot
systemctl status vm-snapshot.timer
```

### 4.3 Verificar Logs

```bash
# Na VM
ssh opc@192.168.122.100 "tail -f /var/log/backup.log"
ssh opc@192.168.122.100 "tail -f /var/log/backup-monitor.log"

# No host
journalctl -u vm-snapshot.service -f
tail -f /var/log/vm-sync.log
```

---

## Resumo dos Cronogramas

### VM (ol9.4)

| Hora  | Tarefa                      | Script                              |
| ----- | --------------------------- | ----------------------------------- |
| 02:00 | Backup de arquivos e bancos | `/usr/local/bin/backup-projetos.sh` |
| 03:00 | Monitoramento de backups    | `/usr/local/bin/backup-monitor.sh`  |

### Host (wks/notebook)

| Hora  | Tarefa                  | Script                          |
| ----- | ----------------------- | ------------------------------- |
| 04:00 | Snapshot do disco da VM | `/usr/local/bin/snapshot-vm.sh` |

### Sincronização (manual)

| Comando                              | Descrição           |
| ------------------------------------ | ------------------- |
| `sync-vm.sh push <host>`             | Enviar VM completa  |
| `sync-vm.sh pull <host>`             | Baixar VM completa  |
| `sync-vm.sh push <host> --data-only` | Enviar apenas dados |

---

## Troubleshooting

### Avahi não resolve hostname

```bash
# Verificar se o serviço está rodando
systemctl status avahi-daemon

# Verificar se a porta está aberta
sudo ss -ulnp | grep 5353

# Verificar configuração NSS
grep hosts /etc/nsswitch.conf
```

### SSH sem senha não funciona

```bash
# Verificar permissões
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

# Testar conexão com debug
ssh -v ldo@notebook.local
```

### Rsync falha

```bash
# Verificar se rsync está instalado no destino
ssh ldo@notebook.local "which rsync"

# Testar rsync básico
rsync -avP --dry-run /tmp/test.txt ldo@notebook.local:/tmp/
```

### VM não inicia após sync

```bash
# Verificar definição
virsh dumpxml ol9.4 | grep -E "(source file|disk)"

# Reimportar definição
virsh define /tmp/ol9.4.xml

# Verificar logs
journalctl -u libvirtd -f
```
