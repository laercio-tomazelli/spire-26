# Análise do Banco de Dados - SPIRE

**Data da Análise:** 6 de dezembro de 2025  
**Banco Analisado:** `spire_prod_new_01_12`  
**Total de Tabelas:** 103

---

## 📊 Resumo Executivo

O banco atual é um sistema de **Assistência Técnica/Pós-Venda (Service Orders)** que apresenta diversas inconsistências estruturais acumuladas ao longo do desenvolvimento. A recomendação é **criar uma estrutura nova e migrar os dados**.

---

## 🔴 Problemas Críticos Identificados

### 1. Mistura de Idiomas (Português/Inglês)

**Tabelas com nomes em português:**

-   `clifor` (clientes/fornecedores)
-   `fornecedores`
-   `tipodocumento`
-   `tipotrans`
-   `tipores`
-   `ceps`
-   `ufs`
-   `status_acompanhamento`

**Campos em português:**

-   `nome_razao`, `telefone_celular`, `defeito_reclamado`
-   `data_abertura`, `numero_os_cliente`
-   `codigo_status`, `data_fechamento`

**Tabelas corretas (inglês):**

-   `users`, `orders`, `parts`, `partners`, `exchanges`

---

### 2. Nomes Não Padronizados

| Tabela Atual    | Deveria Ser              | Observação                  |
| --------------- | ------------------------ | --------------------------- |
| `os`            | `service_orders`         | Nome muito genérico         |
| `os_follow`     | -                        | DUPLICADA com `os_follows`  |
| `os_follows`    | `service_order_comments` | -                           |
| `clifor`        | `customers`              | Nome obscuro                |
| `fornecedores`  | `suppliers`              | Português                   |
| `tipodocumento` | -                        | Duplica `document_types`    |
| `tipotrans`     | -                        | Duplica `transaction_types` |
| `itemlocs`      | `inventory_locations`    | Nome obscuro                |
| `itemtrans`     | `inventory_transactions` | Nome obscuro                |
| `itempend`      | `pending_items`          | Nome obscuro                |
| `itemres`       | `reserved_items`         | Nome obscuro                |
| `nfs`           | -                        | Duplica `fiscal_invoices`   |
| `ceps`          | `postal_codes`           | Português                   |
| `ufs`           | `states`                 | Português                   |

---

### 3. Tipos de Dados Inconsistentes

#### Timestamps como VARCHAR(0)

```sql
-- Tabela: brands
`created_at` varchar(0) DEFAULT NULL  -- ERRADO!
`updated_at` varchar(0) DEFAULT NULL  -- ERRADO!
```

#### Preços como VARCHAR (deveria ser DECIMAL)

```sql
-- Tabela: parts
`price` varchar(50) DEFAULT '0'
`cost_price` varchar(50) DEFAULT '0'

-- Tabela: itemtrans
`Valor_Unitario` varchar(250) NOT NULL DEFAULT '0'

-- Tabela: os
`valor` varchar(255) NOT NULL DEFAULT '0'
```

#### Booleanos como CHAR/VARCHAR

```sql
-- Deveria ser BOOLEAN ou TINYINT(1)
`Atendida` varchar(1) DEFAULT NULL
`reingresso` char(1) NOT NULL DEFAULT 'N'
`trocado` char(1) NOT NULL DEFAULT 'N'
`negociado` char(1) NOT NULL DEFAULT 'N'
`money_back` char(1) NOT NULL DEFAULT 'N'
```

#### IDs Inconsistentes

```sql
-- Algumas tabelas usam INT
`id` int(11) NOT NULL AUTO_INCREMENT

-- Outras usam BIGINT
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT

-- Alguns IDs não são AUTO_INCREMENT
`id` bigint(20) unsigned NOT NULL  -- Tabela: warehouses
```

---

### 4. Tabelas Duplicadas/Legado

| Tabela Nova         | Tabela Legado             | Status              |
| ------------------- | ------------------------- | ------------------- |
| `os_follows`        | `os_follow`               | Estrutura idêntica! |
| `fiscal_invoices`   | `nfs`                     | Estrutura similar   |
| `document_types`    | `tipodocumento`           | Duplicação          |
| `transaction_types` | `tipotrans`               | Duplicação          |
| `os_invites`        | `service_order_invites`   | Duplicação          |
| `os_schedules`      | `service_order_schedules` | Duplicação          |

---

### 5. Campos com Nomes Estranhos

```sql
-- Padrão antigo (PascalCase, prefixos)
`Codigo` bigint(20)           -- deveria ser `id`
`Cod_Fornecedor` int(11)      -- deveria ser `supplier_id`
`Cod_Trans` int(11)           -- deveria ser `transaction_type_id`
`Num_Trans` int(11)           -- deveria ser `id`
`Desc_Fornecedor` varchar(50) -- deveria ser `name`
`Cod_TipoDoc` int(11)         -- deveria ser `id`
```

---

### 6. Falta de Foreign Keys

Muitas tabelas referenciam outras sem FK definida:

```sql
-- os_parts.numero_os deveria ter FK para os.id
-- Mas é VARCHAR referenciando BIGINT!
`numero_os` varchar(10) DEFAULT NULL  -- ERRADO!

-- Deveria ser:
`service_order_id` bigint(20) unsigned NOT NULL,
FOREIGN KEY (`service_order_id`) REFERENCES `service_orders`(`id`)
```

**Tabelas sem FKs que deveriam ter:**

-   `os_parts` → `os`
-   `os_follow` → `os`
-   `os_costs` → `os`, `costs`
-   `order_follows` → `orders`
-   `orders_items` → `orders`, `parts`
-   `itemtrans` → `warehouses`, `parts`

---

### 7. Charsets Misturados

```sql
-- Charset correto
utf8mb4_unicode_ci

-- Charsets problemáticos encontrados
utf8mb3_uca1400_ai_ci  -- Versão antiga do UTF8
latin1_swedish_ci       -- ⚠️ Não suporta acentos corretamente!
```

**Tabelas com latin1 (problemático):**

-   `cache`
-   `cache_locks`
-   `ceps`
-   `job_batches`
-   `sessions`

---

## 📋 Módulos Identificados

### 1. Service Orders (OS) - Core

**Tabelas principais:**

-   `os` - Ordens de serviço
-   `os_parts` - Peças utilizadas na OS
-   `os_follows` / `os_follow` - Acompanhamento
-   `os_costs` - Custos da OS
-   `os_evidence_files` - Arquivos de evidência
-   `os_closings` - Fechamento de OS
-   `os_invites` - Convites para postos
-   `os_schedules` - Agendamentos

### 2. Customers (Clientes)

**Tabelas:**

-   `clifor` - Clientes/Fornecedores (tabela mista)
-   `clifor_changes` - Histórico de alterações
-   `contacts` - Contatos

### 3. Partners (Postos Autorizados)

**Tabelas:**

-   `partners` - Cadastro de postos
-   `contacts` - Contatos do posto

### 4. Parts/Inventory (Peças e Estoque)

**Tabelas:**

-   `parts` - Cadastro de peças
-   `itemlocs` - Localização/estoque
-   `itemtrans` - Transações de estoque
-   `itempend` - Pendências
-   `itemres` - Reservas
-   `warehouses` - Depósitos
-   `part_transactions` - Transações (nova)
-   `part_reserves` - Reservas (nova)

### 5. Orders (Pedidos de Peças)

**Tabelas:**

-   `orders` - Pedidos
-   `orders_items` - Itens do pedido
-   `orders_nfs` - NFs do pedido
-   `order_follows` - Acompanhamento
-   `orders_statuses` - Status

### 6. Exchanges (Trocas)

**Tabelas:**

-   `exchanges` - Solicitações de troca
-   `exchange_reasons` - Motivos
-   `ex_follows` - Acompanhamento
-   `ex_evidence_files` - Evidências
-   `ex_statuses` - Status

### 7. Invoices (Notas Fiscais)

**Tabelas:**

-   `fiscal_invoices` - NFs (nova)
-   `fiscal_invoice_items` - Itens
-   `fiscal_invoice_follows` - Acompanhamento
-   `nfs` - NFs (legado)

### 8. Users/Permissions

**Tabelas:**

-   `users` - Usuários
-   `roles` - Papéis
-   `permissions` - Permissões
-   `teams` - Times
-   `role_user`, `permission_user`, `team_user` - Pivots

### 9. Integrações

**Tabelas:**

-   `bling_tokens` - Integração ERP Bling

### 10. Lookup Tables (Auxiliares)

-   `brands` - Marcas
-   `product_models` - Modelos de produtos
-   `product_types` - Tipos de produtos
-   `service_statuses` - Status de serviço
-   `service_types` - Tipos de serviço
-   `service_locations` - Locais de atendimento
-   `repair_types` - Tipos de reparo
-   `tracking_statuses` - Status de rastreamento
-   `document_types` - Tipos de documento
-   `transaction_types` - Tipos de transação
-   `shipping_company` - Transportadoras
-   `ufs` - Estados
-   `ceps` - CEPs

---

## 🎯 Recomendação: Criar Banco Novo

### Motivos:

1. ✅ Muitas inconsistências acumuladas
2. ✅ Tabelas duplicadas que precisam ser consolidadas
3. ✅ Tipos de dados incorretos que precisam correção
4. ✅ Falta de integridade referencial (FKs)
5. ✅ Oportunidade de aplicar convenções Laravel
6. ✅ Código novo sem "gambiarras" de compatibilidade

### Convenções Laravel a Aplicar:

-   Nomes de tabelas: plural, snake_case, inglês
-   Nomes de campos: snake_case, inglês
-   Primary key: `id` (bigint unsigned auto_increment)
-   Foreign keys: `{tabela_singular}_id`
-   Timestamps: `created_at`, `updated_at` (timestamp)
-   Soft deletes: `deleted_at` (timestamp nullable)
-   Booleanos: tinyint(1) com default 0 ou 1
-   Preços/valores: decimal(10,2) ou decimal(12,4)

---

## 📝 Próximos Passos

1. **Documentar regras de negócio** de cada módulo
2. **Desenhar nova estrutura** padronizada
3. **Criar mapeamento** tabela antiga → tabela nova
4. **Desenvolver scripts** de migração de dados
5. **Testar migração** em ambiente de staging
6. **Executar migração** em produção

---

## 📁 Arquivos Relacionados

-   `schema_dump.sql` - Dump do schema atual (sem dados)

---

## ✏️ Notas

_Adicione aqui observações durante o processo de refatoração_
