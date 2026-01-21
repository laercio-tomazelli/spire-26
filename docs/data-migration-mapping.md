# Mapeamento de Migração de Dados - SPIRE

Este documento descreve o mapeamento entre as tabelas/campos do banco legado (`spire_prod_new_01_12`) e a nova estrutura Laravel.

## Índice

1. [Visão Geral](#visão-geral)
2. [Tabelas de Tenant](#tabelas-de-tenant)
3. [Fabricantes e Marcas](#fabricantes-e-marcas)
4. [Produtos](#produtos)
5. [Peças](#peças)
6. [Parceiros (Autorizadas)](#parceiros-autorizadas)
7. [Clientes](#clientes)
8. [Ordens de Serviço](#ordens-de-serviço)
9. [Pedidos](#pedidos)
10. [Trocas](#trocas)
11. [Notas Fiscais](#notas-fiscais)
12. [Fechamentos Mensais](#fechamentos-mensais)
13. [Estoque](#estoque)
14. [Expedição](#expedição)
15. [Call Center](#call-center)
16. [Usuários e ACL](#usuários-e-acl)
17. [Tabelas Descartadas](#tabelas-descartadas)
18. [Scripts de Migração](#scripts-de-migração)

---

## Visão Geral

### Convenções de Mapeamento

| Aspecto      | Legado                                  | Novo                            |
| ------------ | --------------------------------------- | ------------------------------- |
| Nomenclatura | Mistura PT/EN (`os_`, `nf_`, `pedido_`) | Inglês padronizado              |
| Timestamps   | `dt_cadastro`, `dt_alteracao`           | `created_at`, `updated_at`      |
| Soft Delete  | Não existe                              | `deleted_at`                    |
| Booleanos    | `CHAR(1)` ('S'/'N')                     | `BOOLEAN`                       |
| Decimais     | `VARCHAR` ou `DOUBLE`                   | `DECIMAL(precision, scale)`     |
| Multi-tenant | Não existe                              | `tenant_id` em todas as tabelas |
| Foreign Keys | Ausentes ou inconsistentes              | Todas definidas com constraints |

### Tenant Padrão

Como o sistema legado é single-tenant, todos os registros migrados receberão `tenant_id = 1`.

---

## Tabelas de Tenant

### Nova tabela: `tenants`

**Origem:** Não existe no legado (criar registro padrão)

```sql
INSERT INTO tenants (id, name, slug, document, is_active, created_at)
VALUES (1, 'SPIRE Principal', 'spire-principal', NULL, true, NOW());
```

---

## Fabricantes e Marcas

### `fabricantes` → `manufacturers`

| Campo Legado  | Campo Novo   | Transformação           |
| ------------- | ------------ | ----------------------- |
| `id`          | `id`         | Direto                  |
| `nome`        | `name`       | Direto                  |
| `codigo`      | `code`       | Direto                  |
| `ativo`       | `is_active`  | 'S' → true, 'N' → false |
| `dt_cadastro` | `created_at` | Direto                  |
| -             | `tenant_id`  | Fixo: 1                 |
| -             | `slug`       | Gerar de `name`         |

```sql
INSERT INTO manufacturers (tenant_id, name, slug, code, is_active, created_at)
SELECT
    1,
    nome,
    LOWER(REPLACE(REPLACE(nome, ' ', '-'), '.', '')),
    codigo,
    CASE WHEN ativo = 'S' THEN 1 ELSE 0 END,
    COALESCE(dt_cadastro, NOW())
FROM fabricantes;
```

### `marcas` → `brands`

| Campo Legado    | Campo Novo        | Transformação   |
| --------------- | ----------------- | --------------- |
| `id`            | `id`              | Direto          |
| `fabricante_id` | `manufacturer_id` | FK              |
| `nome`          | `name`            | Direto          |
| `codigo`        | `code`            | Direto          |
| `ativo`         | `is_active`       | 'S' → true      |
| `dt_cadastro`   | `created_at`      | Direto          |
| -               | `tenant_id`       | Fixo: 1         |
| -               | `slug`            | Gerar de `name` |

```sql
INSERT INTO brands (tenant_id, manufacturer_id, name, slug, code, is_active, created_at)
SELECT
    1,
    fabricante_id,
    nome,
    LOWER(REPLACE(REPLACE(nome, ' ', '-'), '.', '')),
    codigo,
    CASE WHEN ativo = 'S' THEN 1 ELSE 0 END,
    COALESCE(dt_cadastro, NOW())
FROM marcas;
```

---

## Produtos

### `linha_produto` → `product_lines`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `marca_id`   | `brand_id`  | FK            |
| `nome`       | `name`      | Direto        |
| `codigo`     | `code`      | Direto        |
| `ativo`      | `is_active` | 'S' → true    |
| -            | `tenant_id` | Fixo: 1       |
| -            | `slug`      | Gerar         |

### `categoria_produto` → `product_categories`

| Campo Legado | Campo Novo    | Transformação |
| ------------ | ------------- | ------------- |
| `id`         | `id`          | Direto        |
| `nome`       | `name`        | Direto        |
| `descricao`  | `description` | Direto        |
| `ativo`      | `is_active`   | 'S' → true    |
| -            | `tenant_id`   | Fixo: 1       |
| -            | `slug`        | Gerar         |

### `modelos` / `produto` → `product_models`

| Campo Legado     | Campo Novo        | Transformação          |
| ---------------- | ----------------- | ---------------------- |
| `id`             | `id`              | Direto                 |
| `marca_id`       | `brand_id`        | FK                     |
| `linha_id`       | `product_line_id` | FK (nullable)          |
| `categoria_id`   | `category_id`     | FK (nullable)          |
| `nome`           | `name`            | Direto                 |
| `codigo`         | `code`            | Direto                 |
| `sku`            | `sku`             | Direto                 |
| `ean`            | `barcode`         | Direto                 |
| `garantia_meses` | `warranty_months` | Direto                 |
| `peso`           | `weight`          | Converter para DECIMAL |
| `ativo`          | `is_active`       | 'S' → true             |
| -                | `tenant_id`       | Fixo: 1                |
| -                | `slug`            | Gerar                  |

```sql
INSERT INTO product_models (
    tenant_id, brand_id, product_line_id, category_id,
    name, slug, code, sku, barcode, warranty_months, weight, is_active, created_at
)
SELECT
    1,
    marca_id,
    linha_id,
    categoria_id,
    nome,
    LOWER(REPLACE(REPLACE(nome, ' ', '-'), '.', '')),
    codigo,
    sku,
    ean,
    COALESCE(garantia_meses, 12),
    CAST(NULLIF(peso, '') AS DECIMAL(10,3)),
    CASE WHEN ativo = 'S' THEN 1 ELSE 0 END,
    COALESCE(dt_cadastro, NOW())
FROM modelos;
```

---

## Peças

### `pecas` → `parts`

| Campo Legado         | Campo Novo      | Transformação     |
| -------------------- | --------------- | ----------------- |
| `id`                 | `id`            | Direto            |
| `marca_id`           | `brand_id`      | FK                |
| `codigo`             | `code`          | Direto            |
| `sku`                | `sku`           | Direto            |
| `nome` / `descricao` | `name`          | Direto            |
| `unidade`            | `unit`          | Direto            |
| `valor_custo`        | `unit_cost`     | VARCHAR → DECIMAL |
| `valor_venda`        | `unit_price`    | VARCHAR → DECIMAL |
| `estoque_minimo`     | `minimum_stock` | Direto            |
| `estoque_maximo`     | `maximum_stock` | Direto            |
| `ponto_reposicao`    | `reorder_point` | Direto            |
| `ncm`                | `ncm`           | Direto            |
| `peso`               | `weight`        | VARCHAR → DECIMAL |
| `ativo`              | `is_active`     | 'S' → true        |
| -                    | `tenant_id`     | Fixo: 1           |

```sql
INSERT INTO parts (
    tenant_id, brand_id, code, sku, name, unit,
    unit_cost, unit_price, minimum_stock, maximum_stock, reorder_point,
    ncm, weight, is_active, created_at
)
SELECT
    1,
    marca_id,
    codigo,
    sku,
    COALESCE(nome, descricao),
    COALESCE(unidade, 'UN'),
    CAST(NULLIF(REPLACE(valor_custo, ',', '.'), '') AS DECIMAL(10,2)),
    CAST(NULLIF(REPLACE(valor_venda, ',', '.'), '') AS DECIMAL(10,2)),
    COALESCE(estoque_minimo, 0),
    estoque_maximo,
    ponto_reposicao,
    ncm,
    CAST(NULLIF(REPLACE(peso, ',', '.'), '') AS DECIMAL(10,3)),
    CASE WHEN ativo = 'S' THEN 1 ELSE 0 END,
    COALESCE(dt_cadastro, NOW())
FROM pecas;
```

### `peca_modelo` → `bill_of_materials`

| Campo Legado | Campo Novo         | Transformação |
| ------------ | ------------------ | ------------- |
| `id`         | `id`               | Direto        |
| `modelo_id`  | `product_model_id` | FK            |
| `peca_id`    | `part_id`          | FK            |
| `quantidade` | `quantity`         | Direto        |
| -            | `tenant_id`        | Fixo: 1       |

---

## Parceiros (Autorizadas)

### `autorizadas` → `partners`

| Campo Legado     | Campo Novo               | Transformação                        |
| ---------------- | ------------------------ | ------------------------------------ |
| `id`             | `id`                     | Direto                               |
| `tipo`           | `type`                   | 'A' → 'authorized', 'R' → 'reseller' |
| `cnpj`           | `document`               | Direto                               |
| `razao_social`   | `legal_name`             | Direto                               |
| `nome_fantasia`  | `trade_name`             | Direto                               |
| `ie`             | `state_registration`     | Direto                               |
| `im`             | `municipal_registration` | Direto                               |
| `telefone`       | `phone`                  | Direto                               |
| `email`          | `email`                  | Direto                               |
| `cep`            | `zip_code`               | Direto                               |
| `estado` / `uf`  | `state`                  | Direto                               |
| `cidade`         | `city`                   | Direto                               |
| `bairro`         | `neighborhood`           | Direto                               |
| `endereco`       | `address`                | Direto                               |
| `numero`         | `address_number`         | Direto                               |
| `complemento`    | `address_complement`     | Direto                               |
| `codigo`         | `code`                   | Direto                               |
| `valor_mao_obra` | `default_labor_rate`     | VARCHAR → DECIMAL                    |
| `ativo`          | `is_active`              | 'S' → true                           |
| -                | `tenant_id`              | Fixo: 1                              |

```sql
INSERT INTO partners (
    tenant_id, type, document, legal_name, trade_name,
    state_registration, municipal_registration, phone, email,
    zip_code, state, city, neighborhood, address, address_number, address_complement,
    code, default_labor_rate, is_active, created_at
)
SELECT
    1,
    CASE tipo WHEN 'A' THEN 'authorized' WHEN 'R' THEN 'reseller' ELSE 'authorized' END,
    cnpj,
    razao_social,
    nome_fantasia,
    ie,
    im,
    telefone,
    email,
    cep,
    COALESCE(estado, uf),
    cidade,
    bairro,
    endereco,
    numero,
    complemento,
    codigo,
    CAST(NULLIF(REPLACE(valor_mao_obra, ',', '.'), '') AS DECIMAL(10,2)),
    CASE WHEN ativo = 'S' THEN 1 ELSE 0 END,
    COALESCE(dt_cadastro, NOW())
FROM autorizadas;
```

### `autorizada_marcas` → `partner_brands`

| Campo Legado    | Campo Novo   | Transformação |
| --------------- | ------------ | ------------- |
| `autorizada_id` | `partner_id` | FK            |
| `marca_id`      | `brand_id`   | FK            |
| `ativo`         | `is_active`  | 'S' → true    |
| -               | `tenant_id`  | Fixo: 1       |

### `autorizada_contatos` → `partner_contacts`

| Campo Legado    | Campo Novo   | Transformação |
| --------------- | ------------ | ------------- |
| `id`            | `id`         | Direto        |
| `autorizada_id` | `partner_id` | FK            |
| `nome`          | `name`       | Direto        |
| `cargo`         | `position`   | Direto        |
| `telefone`      | `phone`      | Direto        |
| `celular`       | `mobile`     | Direto        |
| `email`         | `email`      | Direto        |
| `principal`     | `is_primary` | 'S' → true    |
| -               | `tenant_id`  | Fixo: 1       |

### `autorizada_dados_bancarios` → `partner_bank_accounts`

| Campo Legado       | Campo Novo        | Transformação                       |
| ------------------ | ----------------- | ----------------------------------- |
| `id`               | `id`              | Direto                              |
| `autorizada_id`    | `partner_id`      | FK                                  |
| `banco`            | `bank_name`       | Direto                              |
| `codigo_banco`     | `bank_code`       | Direto                              |
| `agencia`          | `agency`          | Direto                              |
| `conta`            | `account_number`  | Direto                              |
| `tipo_conta`       | `account_type`    | 'CC' → 'checking', 'CP' → 'savings' |
| `titular`          | `holder_name`     | Direto                              |
| `cpf_cnpj_titular` | `holder_document` | Direto                              |
| `pix`              | `pix_key`         | Direto                              |
| `principal`        | `is_primary`      | 'S' → true                          |
| -                  | `tenant_id`       | Fixo: 1                             |

---

## Clientes

### `clientes` → `customers`

| Campo Legado            | Campo Novo           | Transformação                       |
| ----------------------- | -------------------- | ----------------------------------- |
| `id`                    | `id`                 | Direto                              |
| `tipo`                  | `type`               | 'F' → 'individual', 'J' → 'company' |
| `cpf` / `cnpj`          | `document`           | Unificar                            |
| `nome` / `razao_social` | `name`               | Unificar                            |
| `nome_fantasia`         | `trade_name`         | Direto                              |
| `ie`                    | `state_registration` | Direto                              |
| `telefone`              | `phone`              | Direto                              |
| `celular`               | `mobile`             | Direto                              |
| `email`                 | `email`              | Direto                              |
| `cep`                   | `zip_code`           | Direto                              |
| `estado` / `uf`         | `state`              | Direto                              |
| `cidade`                | `city`               | Direto                              |
| `bairro`                | `neighborhood`       | Direto                              |
| `endereco`              | `address`            | Direto                              |
| `numero`                | `address_number`     | Direto                              |
| `complemento`           | `address_complement` | Direto                              |
| `observacao`            | `notes`              | Direto                              |
| -                       | `tenant_id`          | Fixo: 1                             |

```sql
INSERT INTO customers (
    tenant_id, type, document, name, trade_name, state_registration,
    phone, mobile, email, zip_code, state, city, neighborhood,
    address, address_number, address_complement, notes, created_at
)
SELECT
    1,
    CASE tipo WHEN 'F' THEN 'individual' WHEN 'J' THEN 'company' ELSE 'individual' END,
    COALESCE(NULLIF(cpf, ''), cnpj),
    COALESCE(NULLIF(nome, ''), razao_social),
    nome_fantasia,
    ie,
    telefone,
    celular,
    email,
    cep,
    COALESCE(estado, uf),
    cidade,
    bairro,
    endereco,
    numero,
    complemento,
    observacao,
    COALESCE(dt_cadastro, NOW())
FROM clientes;
```

---

## Ordens de Serviço

### Tabelas de Lookup

#### `os_status` → `service_order_statuses`

| Campo Legado | Campo Novo   | Transformação |
| ------------ | ------------ | ------------- |
| `id`         | `id`         | Direto        |
| `nome`       | `name`       | Direto        |
| `cor`        | `color`      | Direto        |
| `ordem`      | `sort_order` | Direto        |
| -            | `slug`       | Gerar         |
| -            | `tenant_id`  | Fixo: 1       |

#### `os_substatus` → `service_order_sub_statuses`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `status_id`  | `status_id` | FK            |
| `nome`       | `name`      | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

#### `tipo_servico` → `service_types`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `nome`       | `name`      | Direto        |
| `codigo`     | `code`      | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

#### `tipo_garantia` → `warranty_types`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `nome`       | `name`      | Direto        |
| `codigo`     | `code`      | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

#### `os_origem` → `service_order_origins`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `nome`       | `name`      | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

#### `defeitos` → `defects`

| Campo Legado         | Campo Novo  | Transformação |
| -------------------- | ----------- | ------------- |
| `id`                 | `id`        | Direto        |
| `codigo`             | `code`      | Direto        |
| `nome` / `descricao` | `name`      | Direto        |
| `marca_id`           | `brand_id`  | FK (nullable) |
| -                    | `tenant_id` | Fixo: 1       |

#### `solucoes` → `solutions`

| Campo Legado         | Campo Novo  | Transformação |
| -------------------- | ----------- | ------------- |
| `id`                 | `id`        | Direto        |
| `codigo`             | `code`      | Direto        |
| `nome` / `descricao` | `name`      | Direto        |
| `marca_id`           | `brand_id`  | FK (nullable) |
| -                    | `tenant_id` | Fixo: 1       |

### `ordens_servico` / `os` → `service_orders`

| Campo Legado                       | Campo Novo                         | Transformação     |
| ---------------------------------- | ---------------------------------- | ----------------- |
| `id`                               | `id`                               | Direto            |
| `numero`                           | `number`                           | Direto            |
| `numero_externo`                   | `external_number`                  | Direto            |
| `cliente_id`                       | `customer_id`                      | FK                |
| `autorizada_id`                    | `partner_id`                       | FK                |
| `modelo_id` / `produto_id`         | `product_model_id`                 | FK                |
| `status_id`                        | `status_id`                        | FK                |
| `substatus_id`                     | `sub_status_id`                    | FK                |
| `tipo_servico_id`                  | `service_type_id`                  | FK                |
| `tipo_garantia_id`                 | `warranty_type_id`                 | FK                |
| `origem_id`                        | `origin_id`                        | FK                |
| `defeito_id`                       | `defect_id`                        | FK                |
| `solucao_id`                       | `solution_id`                      | FK                |
| `usuario_cadastro`                 | `created_by`                       | FK (users)        |
| `tecnico_id` / `usuario_atribuido` | `assigned_to`                      | FK (users)        |
| `numero_serie`                     | `serial_number`                    | Direto            |
| `dt_compra`                        | `purchase_date`                    | Direto            |
| `loja_compra`                      | `purchase_store`                   | Direto            |
| `nf_compra`                        | `purchase_invoice_number`          | Direto            |
| `reclamacao` / `defeito_reclamado` | `customer_complaint`               | Direto            |
| `laudo` / `laudo_tecnico`          | `technical_report`                 | Direto            |
| `observacao_interna`               | `internal_notes`                   | Direto            |
| `endereco_igual_cliente`           | `service_address_same_as_customer` | 'S' → true        |
| `cep_servico`                      | `service_zip_code`                 | Se diferente      |
| `estado_servico`                   | `service_state`                    | Se diferente      |
| `cidade_servico`                   | `service_city`                     | Se diferente      |
| `bairro_servico`                   | `service_neighborhood`             | Se diferente      |
| `endereco_servico`                 | `service_address`                  | Se diferente      |
| `numero_servico`                   | `service_address_number`           | Se diferente      |
| `complemento_servico`              | `service_address_complement`       | Se diferente      |
| `valor_mao_obra`                   | `labor_value`                      | VARCHAR → DECIMAL |
| `valor_pecas`                      | `parts_value`                      | VARCHAR → DECIMAL |
| `valor_custos`                     | `additional_costs_value`           | VARCHAR → DECIMAL |
| `valor_desconto`                   | `discount_value`                   | VARCHAR → DECIMAL |
| `valor_total`                      | `total_value`                      | VARCHAR → DECIMAL |
| `dt_agendamento`                   | `scheduled_date`                   | Direto            |
| `dt_inicio`                        | `started_at`                       | Direto            |
| `dt_conclusao`                     | `completed_at`                     | Direto            |
| `dt_fechamento`                    | `closed_at`                        | Direto            |
| `dt_cadastro`                      | `created_at`                       | Direto            |
| `dt_alteracao`                     | `updated_at`                       | Direto            |
| -                                  | `tenant_id`                        | Fixo: 1           |

```sql
INSERT INTO service_orders (
    tenant_id, number, external_number, customer_id, partner_id, product_model_id,
    status_id, sub_status_id, service_type_id, warranty_type_id, origin_id,
    defect_id, solution_id, created_by, assigned_to,
    serial_number, purchase_date, purchase_store, purchase_invoice_number,
    customer_complaint, technical_report, internal_notes,
    service_address_same_as_customer, service_zip_code, service_state, service_city,
    service_neighborhood, service_address, service_address_number, service_address_complement,
    labor_value, parts_value, additional_costs_value, discount_value, total_value,
    scheduled_date, started_at, completed_at, closed_at, created_at, updated_at
)
SELECT
    1,
    numero,
    numero_externo,
    cliente_id,
    autorizada_id,
    COALESCE(modelo_id, produto_id),
    status_id,
    substatus_id,
    tipo_servico_id,
    tipo_garantia_id,
    origem_id,
    defeito_id,
    solucao_id,
    usuario_cadastro,
    COALESCE(tecnico_id, usuario_atribuido),
    numero_serie,
    dt_compra,
    loja_compra,
    nf_compra,
    COALESCE(reclamacao, defeito_reclamado),
    COALESCE(laudo, laudo_tecnico),
    observacao_interna,
    CASE WHEN endereco_igual_cliente = 'S' THEN 1 ELSE 0 END,
    cep_servico,
    estado_servico,
    cidade_servico,
    bairro_servico,
    endereco_servico,
    numero_servico,
    complemento_servico,
    CAST(NULLIF(REPLACE(valor_mao_obra, ',', '.'), '') AS DECIMAL(10,2)),
    CAST(NULLIF(REPLACE(valor_pecas, ',', '.'), '') AS DECIMAL(10,2)),
    CAST(NULLIF(REPLACE(valor_custos, ',', '.'), '') AS DECIMAL(10,2)),
    CAST(NULLIF(REPLACE(valor_desconto, ',', '.'), '') AS DECIMAL(10,2)),
    CAST(NULLIF(REPLACE(valor_total, ',', '.'), '') AS DECIMAL(10,2)),
    dt_agendamento,
    dt_inicio,
    dt_conclusao,
    dt_fechamento,
    COALESCE(dt_cadastro, NOW()),
    dt_alteracao
FROM ordens_servico;
```

### `os_pecas` → `service_order_parts`

| Campo Legado     | Campo Novo          | Transformação     |
| ---------------- | ------------------- | ----------------- |
| `id`             | `id`                | Direto            |
| `os_id`          | `service_order_id`  | FK                |
| `peca_id`        | `part_id`           | FK                |
| `quantidade`     | `quantity`          | Direto            |
| `valor_unitario` | `unit_price`        | VARCHAR → DECIMAL |
| `valor_total`    | `total_price`       | VARCHAR → DECIMAL |
| `garantia`       | `is_warranty_claim` | 'S' → true        |
| `status`         | `status`            | Mapear para enum  |
| -                | `tenant_id`         | Fixo: 1           |

### `os_custos` → `service_order_costs`

| Campo Legado | Campo Novo         | Transformação     |
| ------------ | ------------------ | ----------------- |
| `id`         | `id`               | Direto            |
| `os_id`      | `service_order_id` | FK                |
| `descricao`  | `description`      | Direto            |
| `valor`      | `value`            | VARCHAR → DECIMAL |
| -            | `tenant_id`        | Fixo: 1           |

### `os_comentarios` → `service_order_comments`

| Campo Legado  | Campo Novo         | Transformação |
| ------------- | ------------------ | ------------- |
| `id`          | `id`               | Direto        |
| `os_id`       | `service_order_id` | FK            |
| `usuario_id`  | `user_id`          | FK            |
| `comentario`  | `content`          | Direto        |
| `interno`     | `is_internal`      | 'S' → true    |
| `dt_cadastro` | `created_at`       | Direto        |
| -             | `tenant_id`        | Fixo: 1       |

### `os_suporte` → `service_order_supports`

| Campo Legado  | Campo Novo         | Transformação |
| ------------- | ------------------ | ------------- |
| `id`          | `id`               | Direto        |
| `os_id`       | `service_order_id` | FK            |
| `usuario_id`  | `user_id`          | FK            |
| `tipo`        | `type`             | Direto        |
| `descricao`   | `description`      | Direto        |
| `resposta`    | `response`         | Direto        |
| `dt_cadastro` | `created_at`       | Direto        |
| -             | `tenant_id`        | Fixo: 1       |

### `os_convites` → `service_order_invites`

| Campo Legado    | Campo Novo         | Transformação |
| --------------- | ------------------ | ------------- |
| `id`            | `id`               | Direto        |
| `os_id`         | `service_order_id` | FK            |
| `autorizada_id` | `partner_id`       | FK            |
| `status`        | `status`           | Mapear        |
| `enviado_em`    | `sent_at`          | Direto        |
| `respondido_em` | `responded_at`     | Direto        |
| `mensagem`      | `message`          | Direto        |
| -               | `tenant_id`        | Fixo: 1       |

### `os_agendamentos` → `service_order_schedules`

| Campo Legado     | Campo Novo         | Transformação |
| ---------------- | ------------------ | ------------- |
| `id`             | `id`               | Direto        |
| `os_id`          | `service_order_id` | FK            |
| `dt_agendamento` | `scheduled_date`   | Direto        |
| `periodo`        | `period`           | Direto        |
| `confirmado`     | `is_confirmed`     | 'S' → true    |
| `observacao`     | `notes`            | Direto        |
| -                | `tenant_id`        | Fixo: 1       |

### `os_evidencias` → `service_order_evidences`

| Campo Legado | Campo Novo         | Transformação    |
| ------------ | ------------------ | ---------------- |
| `id`         | `id`               | Direto           |
| `os_id`      | `service_order_id` | FK               |
| `tipo`       | `type`             | Mapear para enum |
| `arquivo`    | `file_path`        | Direto           |
| `nome`       | `file_name`        | Direto           |
| `descricao`  | `description`      | Direto           |
| -            | `tenant_id`        | Fixo: 1          |

---

## Pedidos

### `pedidos` → `orders`

| Campo Legado    | Campo Novo    | Transformação                  |
| --------------- | ------------- | ------------------------------ |
| `id`            | `id`          | Direto                         |
| `numero`        | `number`      | Direto                         |
| `tipo`          | `type`        | 'C' → 'purchase', 'V' → 'sale' |
| `autorizada_id` | `partner_id`  | FK                             |
| `status`        | `status`      | Mapear para enum               |
| `valor_total`   | `total_value` | VARCHAR → DECIMAL              |
| `observacao`    | `notes`       | Direto                         |
| `dt_cadastro`   | `created_at`  | Direto                         |
| -               | `tenant_id`   | Fixo: 1                        |

### `pedido_itens` → `order_items`

| Campo Legado     | Campo Novo    | Transformação     |
| ---------------- | ------------- | ----------------- |
| `id`             | `id`          | Direto            |
| `pedido_id`      | `order_id`    | FK                |
| `peca_id`        | `part_id`     | FK                |
| `quantidade`     | `quantity`    | Direto            |
| `valor_unitario` | `unit_price`  | VARCHAR → DECIMAL |
| `valor_total`    | `total_price` | VARCHAR → DECIMAL |
| -                | `tenant_id`   | Fixo: 1           |

---

## Trocas

### `trocas_status` → `exchange_statuses`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `nome`       | `name`      | Direto        |
| `cor`        | `color`     | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

### `trocas_motivos` → `exchange_reasons`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `id`         | `id`        | Direto        |
| `nome`       | `name`      | Direto        |
| `codigo`     | `code`      | Direto        |
| -            | `slug`      | Gerar         |
| -            | `tenant_id` | Fixo: 1       |

### `trocas` → `exchanges`

| Campo Legado        | Campo Novo             | Transformação |
| ------------------- | ---------------------- | ------------- |
| `id`                | `id`                   | Direto        |
| `numero`            | `number`               | Direto        |
| `os_id`             | `service_order_id`     | FK            |
| `cliente_id`        | `customer_id`          | FK            |
| `modelo_id`         | `product_model_id`     | FK            |
| `modelo_novo_id`    | `new_product_model_id` | FK            |
| `status_id`         | `status_id`            | FK            |
| `motivo_id`         | `reason_id`            | FK            |
| `numero_serie`      | `serial_number`        | Direto        |
| `numero_serie_novo` | `new_serial_number`    | Direto        |
| `laudo`             | `report`               | Direto        |
| `dt_cadastro`       | `created_at`           | Direto        |
| -                   | `tenant_id`            | Fixo: 1       |

---

## Notas Fiscais

### `notas_fiscais` / `nf` → `invoices`

| Campo Legado     | Campo Novo         | Transformação     |
| ---------------- | ------------------ | ----------------- |
| `id`             | `id`               | Direto            |
| `numero`         | `number`           | Direto            |
| `serie`          | `series`           | Direto            |
| `chave`          | `access_key`       | Direto            |
| `tipo`           | `type`             | Mapear            |
| `os_id`          | `service_order_id` | FK                |
| `pedido_id`      | `order_id`         | FK                |
| `autorizada_id`  | `partner_id`       | FK                |
| `cliente_id`     | `customer_id`      | FK                |
| `status`         | `status`           | Mapear para enum  |
| `valor_produtos` | `products_value`   | VARCHAR → DECIMAL |
| `valor_servicos` | `services_value`   | VARCHAR → DECIMAL |
| `valor_desconto` | `discount_value`   | VARCHAR → DECIMAL |
| `valor_frete`    | `shipping_value`   | VARCHAR → DECIMAL |
| `valor_total`    | `total_value`      | VARCHAR → DECIMAL |
| `xml`            | `xml_content`      | Direto            |
| `pdf_url`        | `pdf_url`          | Direto            |
| `bling_id`       | `external_id`      | Direto            |
| `dt_emissao`     | `issued_at`        | Direto            |
| `dt_cadastro`    | `created_at`       | Direto            |
| -                | `tenant_id`        | Fixo: 1           |

---

## Fechamentos Mensais

### `fechamentos` → `monthly_closings`

| Campo Legado      | Campo Novo        | Transformação     |
| ----------------- | ----------------- | ----------------- |
| `id`              | `id`              | Direto            |
| `autorizada_id`   | `partner_id`      | FK                |
| `mes`             | `month`           | Direto (INT)      |
| `ano`             | `year`            | Direto (INT)      |
| `referencia`      | `reference`       | 'YYYY-MM' format  |
| `status`          | `status`          | Mapear para enum  |
| `valor_total`     | `total_value`     | VARCHAR → DECIMAL |
| `valor_mao_obra`  | `labor_value`     | VARCHAR → DECIMAL |
| `valor_pecas`     | `parts_value`     | VARCHAR → DECIMAL |
| `valor_descontos` | `discounts_value` | VARCHAR → DECIMAL |
| `valor_liquido`   | `net_value`       | VARCHAR → DECIMAL |
| `dt_fechamento`   | `closed_at`       | Direto            |
| `dt_pagamento`    | `paid_at`         | Direto            |
| -                 | `tenant_id`       | Fixo: 1           |

### `fechamento_itens` → `monthly_closing_items`

| Campo Legado     | Campo Novo           | Transformação     |
| ---------------- | -------------------- | ----------------- |
| `id`             | `id`                 | Direto            |
| `fechamento_id`  | `monthly_closing_id` | FK                |
| `os_id`          | `service_order_id`   | FK                |
| `valor_mao_obra` | `labor_value`        | VARCHAR → DECIMAL |
| `valor_pecas`    | `parts_value`        | VARCHAR → DECIMAL |
| `valor_desconto` | `discount_value`     | VARCHAR → DECIMAL |
| `valor_total`    | `total_value`        | VARCHAR → DECIMAL |
| -                | `tenant_id`          | Fixo: 1           |

---

## Estoque

### `estoque_locais` / `depositos` → `warehouses`

| Campo Legado    | Campo Novo   | Transformação |
| --------------- | ------------ | ------------- |
| `id`            | `id`         | Direto        |
| `autorizada_id` | `partner_id` | FK (nullable) |
| `nome`          | `name`       | Direto        |
| `tipo`          | `type`       | Mapear        |
| `ativo`         | `is_active`  | 'S' → true    |
| -               | `tenant_id`  | Fixo: 1       |

### `estoque` → `inventory_items`

| Campo Legado               | Campo Novo          | Transformação |
| -------------------------- | ------------------- | ------------- |
| `id`                       | `id`                | Direto        |
| `deposito_id` / `local_id` | `warehouse_id`      | FK            |
| `peca_id`                  | `part_id`           | FK            |
| `quantidade`               | `quantity`          | Direto        |
| `quantidade_reservada`     | `reserved_quantity` | Direto        |
| -                          | `tenant_id`         | Fixo: 1       |

### `estoque_movimentacoes` → `inventory_transactions`

| Campo Legado          | Campo Novo                        | Transformação           |
| --------------------- | --------------------------------- | ----------------------- |
| `id`                  | `id`                              | Direto                  |
| `deposito_id`         | `warehouse_id`                    | FK                      |
| `peca_id`             | `part_id`                         | FK                      |
| `tipo`                | `type`                            | 'E' → 'in', 'S' → 'out' |
| `quantidade`          | `quantity`                        | Direto                  |
| `quantidade_anterior` | `previous_quantity`               | Direto                  |
| `quantidade_nova`     | `new_quantity`                    | Direto                  |
| `motivo`              | `reason`                          | Mapear para enum        |
| `os_id`               | `reference_type` + `reference_id` | Polymorphic             |
| `usuario_id`          | `user_id`                         | FK                      |
| `observacao`          | `notes`                           | Direto                  |
| `dt_cadastro`         | `created_at`                      | Direto                  |
| -                     | `tenant_id`                       | Fixo: 1                 |

---

## Expedição

### `transportadoras` → `carriers`

| Campo Legado   | Campo Novo     | Transformação |
| -------------- | -------------- | ------------- |
| `id`           | `id`           | Direto        |
| `nome`         | `name`         | Direto        |
| `codigo`       | `code`         | Direto        |
| `cnpj`         | `document`     | Direto        |
| `url_rastreio` | `tracking_url` | Direto        |
| `ativo`        | `is_active`    | 'S' → true    |
| -              | `tenant_id`    | Fixo: 1       |

### `expedicoes` → `shipments`

| Campo Legado        | Campo Novo         | Transformação    |
| ------------------- | ------------------ | ---------------- |
| `id`                | `id`               | Direto           |
| `numero`            | `number`           | Direto           |
| `transportadora_id` | `carrier_id`       | FK               |
| `codigo_rastreio`   | `tracking_code`    | Direto           |
| `status`            | `status`           | Mapear para enum |
| `tipo`              | `type`             | Mapear           |
| `origem_tipo`       | `origin_type`      | Direto           |
| `origem_id`         | `origin_id`        | Direto           |
| `destino_tipo`      | `destination_type` | Direto           |
| `destino_id`        | `destination_id`   | Direto           |
| `dt_envio`          | `shipped_at`       | Direto           |
| `dt_entrega`        | `delivered_at`     | Direto           |
| -                   | `tenant_id`        | Fixo: 1          |

---

## Call Center

### `callcenter_chamadas` → `call_center_calls`

| Campo Legado  | Campo Novo         | Transformação    |
| ------------- | ------------------ | ---------------- |
| `id`          | `id`               | Direto           |
| `protocolo`   | `protocol`         | Direto           |
| `cliente_id`  | `customer_id`      | FK               |
| `os_id`       | `service_order_id` | FK               |
| `tipo`        | `type`             | Mapear           |
| `canal`       | `channel`          | Mapear           |
| `assunto`     | `subject`          | Direto           |
| `descricao`   | `description`      | Direto           |
| `status`      | `status`           | Mapear para enum |
| `prioridade`  | `priority`         | Mapear           |
| `usuario_id`  | `user_id`          | FK               |
| `dt_cadastro` | `created_at`       | Direto           |
| -             | `tenant_id`        | Fixo: 1          |

---

## Usuários e ACL

### `usuarios` → `users`

| Campo Legado    | Campo Novo      | Transformação     |
| --------------- | --------------- | ----------------- |
| `id`            | `id`            | Direto            |
| `nome`          | `name`          | Direto            |
| `email`         | `email`         | Direto            |
| `senha`         | `password`      | Rehash necessário |
| `tipo`          | -               | Migrar para roles |
| `autorizada_id` | `partner_id`    | FK                |
| `ativo`         | `is_active`     | 'S' → true        |
| `ultimo_login`  | `last_login_at` | Direto            |
| `dt_cadastro`   | `created_at`    | Direto            |
| -               | `tenant_id`     | Fixo: 1           |

**Nota:** As senhas do sistema legado provavelmente usam algoritmo diferente. Será necessário:

1. Migrar com senha temporária
2. Forçar reset de senha no primeiro login
3. Ou implementar autenticação que suporte ambos formatos temporariamente

### `perfis` → `roles`

| Campo Legado | Campo Novo    | Transformação |
| ------------ | ------------- | ------------- |
| `id`         | `id`          | Direto        |
| `nome`       | `name`        | Direto        |
| `descricao`  | `description` | Direto        |
| -            | `slug`        | Gerar         |
| -            | `tenant_id`   | Fixo: 1       |

### `permissoes` → `permissions`

| Campo Legado | Campo Novo  | Transformação         |
| ------------ | ----------- | --------------------- |
| `id`         | `id`        | Direto                |
| `modulo`     | `module`    | Direto                |
| `acao`       | `action`    | Direto                |
| `nome`       | `name`      | Direto                |
| -            | `slug`      | Gerar (module.action) |
| -            | `tenant_id` | Fixo: 1               |

### `usuario_perfis` → `user_roles`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `usuario_id` | `user_id`   | FK            |
| `perfil_id`  | `role_id`   | FK            |
| -            | `tenant_id` | Fixo: 1       |

### `usuario_marcas` → `user_brands`

| Campo Legado | Campo Novo  | Transformação |
| ------------ | ----------- | ------------- |
| `usuario_id` | `user_id`   | FK            |
| `marca_id`   | `brand_id`  | FK            |
| -            | `tenant_id` | Fixo: 1       |

---

## Tabelas Descartadas

As seguintes tabelas do banco legado **não serão migradas**:

| Tabela Legada                    | Motivo                                       |
| -------------------------------- | -------------------------------------------- |
| `sessions`                       | Tabela de sessões PHP, recriada pelo Laravel |
| `password_resets`                | Recriada pelo Laravel                        |
| `migrations`                     | Específica do sistema antigo                 |
| `jobs` / `failed_jobs`           | Recriadas pelo Laravel                       |
| `cache`                          | Recriada pelo Laravel                        |
| `log_*` (tabelas de log antigas) | Substituídas por `audit_logs`                |
| `temp_*` (tabelas temporárias)   | Não necessárias                              |
| `bkp_*` (tabelas de backup)      | Não necessárias                              |
| Tabelas duplicadas/obsoletas     | Identificar e listar                         |

---

## Scripts de Migração

### Ordem de Execução

```bash
# 1. Criar estrutura nova
php artisan migrate:fresh

# 2. Executar migração de dados (ordem importante!)
php artisan db:seed --class=DataMigrationSeeder
```

### Classe de Migração de Dados

Criar `database/seeders/DataMigrationSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataMigrationSeeder extends Seeder
{
    protected $legacyConnection = 'legacy_mysql';

    public function run(): void
    {
        $this->command->info('Iniciando migração de dados...');

        // Ordem de migração respeitando FKs
        $this->call([
            MigrateTenantSeeder::class,
            MigrateManufacturersSeeder::class,
            MigrateBrandsSeeder::class,
            MigrateProductLinesSeeder::class,
            MigrateProductCategoriesSeeder::class,
            MigrateProductModelsSeeder::class,
            MigratePartsSeeder::class,
            MigratePartnersSeeder::class,
            MigrateCustomersSeeder::class,
            MigrateUsersSeeder::class,
            MigrateRolesSeeder::class,
            MigratePermissionsSeeder::class,
            MigrateLookupTablesSeeder::class,
            MigrateServiceOrdersSeeder::class,
            MigrateOrdersSeeder::class,
            MigrateExchangesSeeder::class,
            MigrateInvoicesSeeder::class,
            MigrateInventorySeeder::class,
            MigrateShipmentsSeeder::class,
            MigrateClosingsSeeder::class,
        ]);

        $this->command->info('Migração concluída!');
    }
}
```

### Configuração da Conexão Legada

Adicionar em `config/database.php`:

```php
'connections' => [
    // ... conexões existentes

    'legacy_mysql' => [
        'driver' => 'mysql',
        'host' => env('LEGACY_DB_HOST', '127.0.0.1'),
        'port' => env('LEGACY_DB_PORT', '3306'),
        'database' => env('LEGACY_DB_DATABASE', 'spire_prod_new_01_12'),
        'username' => env('LEGACY_DB_USERNAME', 'root'),
        'password' => env('LEGACY_DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => false,
    ],
],
```

### Exemplo de Seeder de Migração

```php
<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateManufacturersSeeder extends Seeder
{
    public function run(): void
    {
        $legacyData = DB::connection('legacy_mysql')
            ->table('fabricantes')
            ->get();

        foreach ($legacyData as $row) {
            Manufacturer::create([
                'id' => $row->id, // Preservar IDs para manter FKs
                'tenant_id' => 1,
                'name' => $row->nome,
                'slug' => Str::slug($row->nome),
                'code' => $row->codigo,
                'is_active' => $row->ativo === 'S',
                'created_at' => $row->dt_cadastro ?? now(),
            ]);
        }

        $this->command->info('Fabricantes migrados: ' . $legacyData->count());
    }
}
```

### Validação Pós-Migração

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidateMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $checks = [
            'manufacturers' => ['fabricantes', 'manufacturers'],
            'brands' => ['marcas', 'brands'],
            'products' => ['modelos', 'product_models'],
            'parts' => ['pecas', 'parts'],
            'partners' => ['autorizadas', 'partners'],
            'customers' => ['clientes', 'customers'],
            'service_orders' => ['ordens_servico', 'service_orders'],
        ];

        foreach ($checks as $name => [$legacy, $new]) {
            $legacyCount = DB::connection('legacy_mysql')->table($legacy)->count();
            $newCount = DB::table($new)->count();

            $status = $legacyCount === $newCount ? '✓' : '✗';
            $this->command->info("{$status} {$name}: {$legacyCount} → {$newCount}");
        }
    }
}
```

---

## Considerações Importantes

### 1. Preservação de IDs

-   Manter os IDs originais para não quebrar relacionamentos
-   Usar `DB::statement('SET FOREIGN_KEY_CHECKS=0')` temporariamente se necessário

### 2. Tratamento de Dados Inválidos

-   Registros com FKs órfãs: logar e pular ou criar registro placeholder
-   Campos obrigatórios nulos: usar valor padrão e logar
-   Dados duplicados: manter primeiro e logar duplicatas

### 3. Conversão de Tipos

-   `VARCHAR` com valores monetários: remover formatação, converter para DECIMAL
-   `CHAR(1)` booleanos: 'S'/'N'/'1'/'0' → true/false
-   Datas inválidas: usar null ou data padrão

### 4. Backup

```bash
# Antes de qualquer migração
mysqldump -u root -p spire_prod_new_01_12 > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 5. Ambiente de Teste

-   Executar migração primeiro em ambiente de desenvolvimento
-   Validar integridade dos dados
-   Testar funcionalidades críticas antes de produção
