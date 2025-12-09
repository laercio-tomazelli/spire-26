# TODO - SPIRE 26

## 📋 Funcionalidades Futuras

### 🔔 Sistema de Comunicação Integrado

> **Prioridade**: Alta  
> **Status**: Planejado  
> **Complexidade**: Alta  
> **Data de Registro**: 09/12/2024

#### 1. Sistema de Notificações/Alertas (Implementar Primeiro)

-   [ ] Tabela `notifications` (usar Laravel Notifications)
-   [ ] Tipos: sistema, usuário, ação requerida
-   [ ] Push via WebSockets (tempo real)
-   [ ] Preferências por tipo de notificação
-   [ ] Opção de envio por email (via Resend)
-   [ ] Badge contador no header
-   [ ] Dropdown de notificações
-   [ ] Página de histórico de notificações

#### 2. Chat em Tempo Real (Implementar Segundo)

> **Infraestrutura base pronta** (Laravel Reverb configurado)

-   [ ] Tabela `conversations` (1:1 e grupos)
-   [ ] Tabela `conversation_participants`
-   [ ] Tabela `chat_messages`
-   [x] Laravel Broadcasting (Pusher/Reverb/Soketi) → **Reverb instalado**
-   [ ] Indicador de digitação
-   [ ] Status online/offline
-   [ ] Confirmação de leitura
-   [ ] Upload de arquivos/imagens
-   [ ] Widget flutuante ou sidebar
-   [ ] Histórico de conversas

#### 3. Sistema de Mensagens Internas (Mais Complexo)

-   [ ] Tabela `messages` (tipo email interno)
-   [ ] Tabela `message_recipients` (to, cc, bcc)
-   [ ] Tabela `message_attachments`
-   [ ] Threads de resposta
-   [ ] Caixa de entrada/enviados/rascunhos/lixeira
-   [ ] Opção de notificação externa via Resend
-   [ ] Busca e filtros
-   [ ] Marcadores/tags

#### Infraestrutura Necessária

-   [x] Escolher provedor WebSocket (Pusher, Laravel Reverb, ou Soketi) → **Laravel Reverb**
-   [x] Configurar Laravel Broadcasting
-   [x] Criar eventos de broadcast (OrderCancelled, OrderUpdated)
-   [x] Implementar listeners no frontend (vanilla JS/TS)
-   [x] Sistema de filas para emails (já configurado com jobs)

#### Arquivos já criados (base para expansão)

-   `app/Events/OrderCancelled.php` - Evento de cancelamento de pedido
-   `app/Events/OrderUpdated.php` - Evento de atualização de pedido
-   `resources/js/events/order-events.ts` - Listener de eventos de pedido
-   `resources/js/events/order-events-example.ts` - Exemplos de uso
-   `routes/channels.php` - Canais: orders, orders.{id}, presence.online

#### Integração Entre Módulos

-   Chat pode enviar notificações
-   Mensagens podem disparar notificações
-   Notificações podem ter link para chat/mensagem
-   Unificação visual no header do usuário

---

## 📝 Notas Técnicas

### Stack Atual

-   **Email**: Resend (configurado e funcionando)
-   **Frontend**: spire-ui (vanilla JS/TS, sem Alpine.js)
-   **Backend**: Laravel 12
-   **Database**: MariaDB 11.4+

### Ordem Sugerida de Implementação

1. **Notificações** - Mais simples, valor imediato, infraestrutura base
2. **Chat** - Utiliza infraestrutura de notificações
3. **Mensagens** - Mais complexo, pode usar ambos os sistemas anteriores

---

## 🖥️ Infraestrutura / DevOps

### Ambiente de Desenvolvimento Local (Docker)

> **Status**: ✅ Implementado (09/12/2025)

-   [x] Docker Compose com Oracle Linux 9
-   [x] NGINX com HTTPS (mkcert)
-   [x] PHP-FPM 8.4
-   [x] MariaDB 11.4
-   [x] Redis 7
-   [x] Supervisor (queue workers + scheduler)
-   [x] Mailpit para testes de email

### Migração do Servidor de Produção (OCI)

> **Prioridade**: Média  
> **Status**: Planejado  
> **Objetivo**: Remover aaPanel e configurar servidor nativo (menor overhead)

#### Tarefas

-   [ ] Criar nova instância OCI (Oracle Linux 9 / Ampere)
-   [ ] Conectar via VS Code + SSH
-   [ ] Instalar PHP 8.4 via Remi repository
-   [ ] Instalar e configurar NGINX
-   [ ] Instalar e configurar MariaDB 11.4
-   [ ] Instalar e configurar Redis
-   [ ] Configurar Supervisor (queue workers + scheduler)
-   [ ] Configurar SSL com Let's Encrypt (Certbot)
-   [ ] Configurar firewall (firewalld)
-   [ ] Migrar banco de dados
-   [ ] Deploy do código via Git
-   [ ] Testar e validar
-   [ ] Apontar DNS para nova instância
-   [ ] Desativar instância antiga com aaPanel

#### Benefícios Esperados

-   ~200MB RAM liberada (sem aaPanel)
-   Menos processos rodando
-   Menor superfície de ataque (sem porta 7800)
-   Controle total da configuração
-   Configuração idêntica ao ambiente de dev (Docker)
