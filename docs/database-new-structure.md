# SPIRE - Nova Estrutura do Banco de Dados

**Data:** 7 de dezembro de 2025  
**Versão:** 1.0  
**Status:** Proposta

---

## 📋 Sobre o Sistema

**SPIRE** (Simple Post-sales Intelligence Resolution) é um ERP focado em gerenciamento de pós-vendas de produtos eletroeletrônicos, linha branca, linha marrom e informática.

---

## 👥 Atores do Sistema

| Ator                 | Descrição                                          |
| -------------------- | -------------------------------------------------- |
| **Spire**            | Fornece, desenvolve e dá suporte ao software       |
| **Cliente**          | Empresas que operam o pós-vendas de Fabricantes    |
| **Fabricante**       | Possui marcas e terceiriza pós-vendas aos Clientes |
| **Posto Autorizado** | Empresas que prestam serviço de reparo/manutenção  |
| **Consumidor**       | Consumidor final que necessita de pós-venda        |
| **Call Center**      | Central de atendimento aos Consumidores            |
| **Transportadora**   | Empresas que transportam Parts                     |

---

## 🏗️ Nova Estrutura Proposta

### Convenções Adotadas

-   **Nomes de tabelas:** plural, snake_case, inglês
-   **Primary key:** `id` (bigint unsigned auto_increment)
-   **Foreign keys:** `{tabela_singular}_id`
-   **Timestamps:** `created_at`, `updated_at`
-   **Soft deletes:** `deleted_at` (quando aplicável)
-   **Booleanos:** `is_*` ou `has_*` (tinyint 1)
-   **Valores monetários:** `decimal(12,2)`
-   **Status/Tipos:** tabelas lookup ou enums

---

## 📦 Módulos e Tabelas

### 1. Core - Tenancy (Multi-tenant)

```
tenants (Clientes da Spire)
├── id
├── name
├── trade_name
├── document (CNPJ)
├── email
├── phone
├── is_active
├── settings (JSON)
├── created_at
├── updated_at
└── deleted_at
```

---

### 2. Fabricantes e Marcas

```
manufacturers (Fabricantes)
├── id
├── tenant_id → tenants
├── name
├── trade_name
├── document (CNPJ)
├── is_active
├── created_at
├── updated_at
└── deleted_at

brands (Marcas)
├── id
├── manufacturer_id → manufacturers
├── name
├── logo_path
├── is_active
├── created_at
└── updated_at

product_lines (Linhas de Produto)
├── id
├── name (Linha Branca, Linha Marrom, Informática)
├── description
├── created_at
└── updated_at

brand_product_line (Pivot: Marca x Linha)
├── id
├── brand_id → brands
├── product_line_id → product_lines
├── created_at
└── updated_at
```

---

### 3. Produtos e Modelos

```
product_categories (Categorias)
├── id
├── product_line_id → product_lines
├── name (TV, Monitor, Geladeira, etc.)
├── description
├── created_at
└── updated_at

product_models (Modelos de Produto)
├── id
├── brand_id → brands
├── product_category_id → product_categories
├── model_code
├── model_name
├── manufacturer_model
├── ean
├── release_date
├── end_of_life_date
├── warranty_months
├── promotional_warranty_months
├── observations
├── is_active
├── created_at
├── updated_at
└── deleted_at
```

---

### 4. Peças (Parts)

```
parts (Peças/Componentes)
├── id
├── tenant_id → tenants
├── part_code (SKU único)
├── description
├── short_description
├── unit (UN, PC, KIT)
├── ncm
├── cest
├── origin (0-Nacional, 1-Importado, etc.)
├── ean
├── ean_packaging
├── manufacturer_code
├── price (decimal 12,2)
├── cost_price (decimal 12,2)
├── net_weight
├── gross_weight
├── width
├── height
├── depth
├── min_stock
├── max_stock
├── location
├── is_display (bool)
├── is_active
├── bling_id (integração)
├── synced_at
├── created_at
├── updated_at
└── deleted_at

bill_of_materials (Lista de Materiais)
├── id
├── product_model_id → product_models
├── part_id → parts
├── quantity
├── line_position
├── is_provided (bool - fornecido pelo fabricante)
├── created_at
└── updated_at
```

---

### 5. Postos Autorizados (Partners)

```
partners (Postos Autorizados)
├── id
├── tenant_id → tenants
├── code (Código único ex: SP002-GBR)
├── document_type (CPF/CNPJ)
├── document
├── state_registration (IE)
├── is_tax_exempt
├── company_name
├── trade_name
├── email
├── email_secondary
├── phone
├── phone_secondary
├── fax
├── contact_name
├── address
├── address_number
├── address_complement
├── neighborhood
├── city
├── state
├── postal_code
├── company_type_id → company_types
├── tax_regime_id → tax_regimes
├── person_type (PF/PJ)
├── status (active, inactive, suspended)
├── level (A, B, C)
├── category
├── bank_code
├── bank_name
├── bank_agency
├── bank_account
├── pix_key
├── pix_key_type_id → pix_key_types
├── account_type (corrente, poupança)
├── bank_observations
├── observations
├── bling_id
├── synced_at
├── created_at
├── updated_at
└── deleted_at

partner_brands (Marcas atendidas pelo Posto)
├── id
├── partner_id → partners
├── brand_id → brands
├── is_active
├── created_at
└── updated_at

partner_product_lines (Linhas atendidas pelo Posto)
├── id
├── partner_id → partners
├── product_line_id → product_lines
├── is_active
├── created_at
└── updated_at

partner_contacts (Contatos do Posto)
├── id
├── partner_id → partners
├── contact_type_id → contact_types
├── name
├── phone
├── email
├── is_primary
├── observations
├── created_at
└── updated_at
```

---

### 6. Consumidores (Customers)

```
customers (Consumidores)
├── id
├── tenant_id → tenants
├── customer_type (PF/PJ)
├── document (CPF/CNPJ)
├── state_registration
├── name
├── trade_name
├── email
├── phone
├── phone_secondary
├── mobile
├── address
├── address_number
├── address_complement
├── neighborhood
├── city
├── city_code (IBGE)
├── state
├── postal_code
├── country
├── country_code
├── birth_date
├── observations
├── is_from_invoice (bool)
├── bling_id
├── synced_at
├── created_at
├── updated_at
└── deleted_at

customer_changes (Auditoria de alterações)
├── id
├── customer_id → customers
├── user_id → users
├── field_name
├── old_value
├── new_value
├── created_at
└── updated_at
```

---

### 7. Ordens de Serviço (Service Orders)

```
service_orders (Ordens de Serviço)
├── id
├── tenant_id → tenants
├── order_number (número sequencial por tenant)
├── protocol
├──
├── # Referências Externas
├── manufacturer_pre_order
├── manufacturer_pre_order_date
├── manufacturer_order
├── manufacturer_order_date
├── partner_order
├── partner_order_date
├── external_id (ID sistema externo TPV)
├──
├── # Relacionamentos
├── customer_id → customers
├── partner_id → partners
├── brand_id → brands
├── product_model_id → product_models
├── product_category_id → product_categories
├──
├── # Dados do Produto
├── model_received (modelo informado)
├── serial_number
├──
├── # Dados da Compra
├── retailer_name
├── purchase_invoice_number
├── purchase_invoice_date
├── purchase_value (decimal 12,2)
├── purchase_invoice_file
├──
├── # Classificação
├── service_location_id → service_locations (Balcão, Domicílio, Depósito)
├── service_order_type_id → service_order_types (Consumidor, Revenda, etc.)
├── service_type_id → service_types (Reparo, Instalação, etc.)
├── repair_type_id → repair_types
├── warranty_type (in_warranty, out_of_warranty)
├──
├── # Status
├── status_id → service_order_statuses
├── tracking_status_id → tracking_statuses
├── accept_status_id → accept_statuses
├── manufacturer_status
├──
├── # Defeito e Reparo
├── reported_defect
├── confirmed_defect
├── defect_condition
├── symptom
├── repair_description
├── accessories
├── conditions
├── observations
├──
├── # Flags
├── is_reentry (bool - reingresso)
├── reentry_order_id → service_orders
├── is_critical (bool)
├── is_no_defect (bool - sem defeito)
├── has_parts_used (bool)
├── is_display (bool)
├──
├── # Troca/Devolução
├── is_exchange (bool)
├── exchange_type (product, refund)
├── exchange_reason
├── exchange_model_id → product_models
├── exchange_negotiated_value (decimal 12,2)
├── exchange_analysis_date
├── exchange_approval_date
├── exchange_analyzed_by → users
├── exchange_result
├──
├── # Custos Adicionais
├── labor_cost (decimal 12,2)
├── distance_km
├── km_cost (decimal 12,2)
├── extra_cost (decimal 12,2)
├── visit_count
├──
├── # Datas do Fluxo
├── opened_at
├── opened_by → users
├── evaluated_at
├── evaluated_by → users
├── repaired_at
├── repaired_by → users
├── closed_at
├── closed_by → users
├── manufacturer_closed_at
├── manufacturer_approved_at
├── manufacturer_approved_by → users
├──
├── # Aceite/Rejeição do Posto
├── accepted_at
├── accepted_by → users
├── rejected_at
├── rejected_by → users
├── rejection_reason
├──
├── # Logística de Entrada
├── entry_invoice_number
├── entry_invoice_date
├── entry_tracking_code
├── received_at
├── received_serial
├──
├── # Logística de Saída
├── exit_invoice_number
├── exit_invoice_date
├── exit_tracking_code
├── exit_sent_at
├── delivered_at
├──
├── # Coleta (para domicílio)
├── collection_invoice_number
├── collection_invoice_date
├── collection_number
├── collection_date
├── scheduled_visit_date
├──
├── # Controle
├── closing_type_id → closing_types
├── is_admin_closed (bool)
├── process_observations
├──
├── created_at
├── updated_at
└── deleted_at

service_order_parts (Peças da OS)
├── id
├── service_order_id → service_orders
├── part_id → parts
├── part_code
├── part_description
├── quantity
├── unit_price (decimal 12,2)
├── total_price (decimal 12,2)
├──
├── # Classificação
├── section
├── defect_code
├── solution_code
├── symptom_code
├── position
├── type (normal, special)
├── request_type (normal, special)
├──
├── # Status
├── status
├── is_approved (bool)
├── approval_reason
├── rejection_reason
├── request_reason
├──
├── # Pedido
├── order_id → orders
├── order_item_id → order_items
├── order_date
├── order_number
├── generates_order (bool)
├──
├── # NF de Envio
├── invoice_number
├── invoice_date
├──
├── # Logística
├── eticket
├── sent_at
├── tracking_code_sent
├── return_date
├── tracking_code_return
├── shipping_observations
├── received_at_cr_date
├──
├── # Recebimento e Aplicação
├── substitute_part_code
├── is_received (bool)
├── received_at
├── is_applied (bool)
├── applied_at
├── shipping_type
├── partner_part_code
├──
├── created_at
└── updated_at

service_order_costs (Custos da OS)
├── id
├── service_order_id → service_orders
├── cost_type_id → cost_types
├── unit_count
├── unit_value (decimal 12,2)
├── total_value (decimal 12,2)
├── variable_value (decimal 12,2)
├── is_approved
├── approved_by → users
├── approved_at
├── observations
├── validation_observations
├── created_at
└── updated_at

service_order_comments (Acompanhamento/Follow-up)
├── id
├── service_order_id → service_orders
├── user_id → users
├── comment
├── comment_type (user, system, import)
├── privacy_id → comment_privacies
├── created_at
└── updated_at

service_order_comment_files (Arquivos dos Comentários)
├── id
├── service_order_comment_id → service_order_comments
├── file_name
├── file_path
├── created_at
└── updated_at

service_order_evidence_files (Evidências)
├── id
├── service_order_id → service_orders
├── evidence_type_id → evidence_types
├── uuid
├── file_name
├── file_path
├── observations
├── created_at
└── updated_at

service_order_technical_support (Suporte Técnico)
├── id
├── service_order_id → service_orders
├── user_id → users
├── message
├── message_type (user, system)
├── origin (partner, manufacturer)
├── status (open, closed)
├── ball_with (partner, manufacturer)
├── privacy_id → comment_privacies
├── created_at
└── updated_at

service_order_technical_support_files
├── id
├── service_order_technical_support_id
├── file_name
├── file_path
├── created_at
└── updated_at

service_order_admin_support (Suporte Administrativo)
├── id
├── service_order_id → service_orders
├── user_id → users
├── message
├── message_type (user, system)
├── origin (partner, manufacturer)
├── status (open, closed)
├── ball_with (partner, manufacturer)
├── privacy_id → comment_privacies
├── created_at
└── updated_at

service_order_admin_support_files
├── id
├── service_order_admin_support_id
├── file_name
├── file_path
├── created_at
└── updated_at

service_order_invites (Convites para Postos)
├── id
├── service_order_id → service_orders
├── partner_id → partners
├── status_id → invite_statuses
├── rejection_reason
├── observations
├── responded_at
├── responded_by → users
├── created_at
└── updated_at

service_order_schedules (Agendamentos)
├── id
├── service_order_invite_id → service_order_invites
├── scheduled_date
├── status_id → schedule_statuses
├── observations
├── created_at
└── updated_at

service_order_changes (Auditoria de Alterações)
├── id
├── service_order_id → service_orders
├── user_id → users
├── field_name
├── old_value
├── new_value
├── created_at
└── updated_at

service_order_document_downloads (Auditoria Downloads)
├── id
├── service_order_id → service_orders
├── user_id → users
├── document_name
├── document_file
├── ip_address
├── user_agent
├── downloaded_at
├── created_at
└── updated_at
```

---

### 8. Pedidos de Peças (Orders)

```
orders (Pedidos)
├── id
├── tenant_id → tenants
├── order_number
├── service_order_id → service_orders
├── exchange_id → exchanges
├── partner_id → partners
├── brand_id → brands
├──
├── # Classificação
├── order_type (parts, exchange, buffer)
├── service_order_type
├──
├── # Status
├── status_id → order_statuses
├── billing_status
├── gateway_status
├──
├── # Valores
├── total_items
├── total_value (decimal 12,2)
├──
├── # Integração Gateway/Bling
├── gateway_order_id
├── gateway_order_date
├── gateway_input_order_id
├── gateway_input_order_date
├── bling_order_id
├── bling_order_date
├──
├── # Datas do Fluxo
├── order_date
├── verified_at
├── separated_at
├── collected_at
├── delivered_at
├── estimated_delivery_date
├──
├── # Aprovação
├── is_approved (bool)
├── approved_at
├── approved_by → users
├──
├── # Cancelamento
├── cancelled_at
├── cancellation_reason
├──
├── observations
├── created_at
├── updated_at
└── deleted_at

order_items (Itens do Pedido)
├── id
├── order_id → orders
├── part_id → parts
├── service_order_part_id → service_order_parts
├── part_code
├── substitute_part_code
├── quantity
├── unit_price (decimal 12,2)
├── icms_value (decimal 12,2)
├── ipi_value (decimal 12,2)
├── st_value (decimal 12,2)
├── total_value (decimal 12,2)
├──
├── # NF
├── invoice_number
├── invoice_date
├── manufacturer_invoice_number
├── manufacturer_invoice_date
├── invoice_binding_id
├── is_invoice_ok (bool)
├──
├── # Status
├── billing_status
├── is_reserved (bool)
├── is_blocked (bool)
├── is_approved (bool)
├──
├── # Datas do Fluxo
├── verified_at
├── verified_by
├── separated_at
├── separated_by
├── collected_at
├──
├── observations
├── created_at
└── updated_at

order_comments (Acompanhamento do Pedido)
├── id
├── order_id → orders
├── user_id → users
├── comment
├── comment_type
├── created_at
└── updated_at

order_invoices (NFs do Pedido)
├── id
├── order_id → orders
├── service_order_number
├── order_number
├── invoice_number
├── invoice_date
├── invoice_file
├── cfop
├── product_code
├── product_name
├── value (decimal 12,2)
├── additional_info
├── invoice_key
├── created_at
└── updated_at
```

---

### 9. Trocas (Exchanges)

```
exchanges (Trocas de Produto)
├── id
├── tenant_id → tenants
├── uuid
├──
├── # Tipo
├── exchange_type (via_partner, direct_consumer)
├──
├── # Relacionamentos
├── service_order_id → service_orders (OS origem)
├── exchange_service_order_id → service_orders (OS troca)
├── customer_id → customers
├── partner_id → partners
├── order_id → orders
├── order_item_id → order_items
├──
├── # Produto Original
├── original_model_id → product_models
├── original_model_name
├── serial_number
├── retailer_name
├── purchase_invoice_number
├── purchase_invoice_date
├── purchase_value (decimal 12,2)
├──
├── # Defeito/Condições
├── reported_defect
├── product_conditions
├──
├── # Decisão de Troca
├── exchange_decision (product, refund)
├── negotiated_value (decimal 12,2)
├── exchange_model_id → product_models
├── exchange_model_name
├── exchange_reason_id → exchange_reasons
├── exchange_reason_text
├──
├── # Status
├── status_id → exchange_statuses
├──
├── # Evidências (paths)
├── invoice_evidence_path
├── label_evidence_path
├── defect_evidence_path
├──
├── # Solicitação
├── requested_by → users
├── requested_at
├──
├── # Aprovação
├── approved_by → users
├── approved_at
├── approval_notes
├── rejection_reason
├──
├── observations
├── created_at
└── updated_at

exchange_reasons (Motivos de Troca)
├── id
├── code
├── description
├── is_active
├── display_order
├── created_at
└── updated_at

exchange_statuses (Status de Troca)
├── id
├── code
├── name
├── color
├── display_order
├── is_active
├── created_at
└── updated_at

exchange_comments (Acompanhamento)
├── id
├── exchange_id → exchanges
├── user_id → users
├── comment
├── comment_type
├── privacy_id → comment_privacies
├── created_at
└── updated_at

exchange_comment_files
├── id
├── exchange_comment_id → exchange_comments
├── file_name
├── file_path
├── created_at
└── updated_at

exchange_evidence_files (Evidências)
├── id
├── exchange_id → exchanges
├── evidence_type_id → evidence_types
├── uuid
├── file_name
├── file_path
├── observations
├── created_at
└── updated_at
```

---

### 10. Estoque (Inventory)

```
warehouses (Depósitos)
├── id
├── tenant_id → tenants
├── code
├── name
├── description
├── location
├── type (main, partner, buffer, defective)
├── brand_id → brands
├── is_brand_default (bool)
├── partner_id → partners (se depósito do posto)
├── bling_id
├── created_at
└── updated_at

inventory_items (Estoque por Depósito)
├── id
├── warehouse_id → warehouses
├── part_id → parts
├── part_code
├── available_quantity
├── reserved_quantity
├── pending_quantity
├── defective_quantity
├── created_at
├── updated_at
└── deleted_at

inventory_transactions (Movimentações)
├── id
├── warehouse_id → warehouses
├── part_id → parts
├── part_code
├── user_id → users
├── transaction_type_id → transaction_types
├── document_type_id → document_types
├── document_number
├── quantity
├── unit_price (decimal 12,2)
├── cost_price (decimal 12,2)
├── observations
├── created_at
└── updated_at

inventory_reserves (Reservas)
├── id
├── warehouse_id → warehouses
├── part_id → parts
├── part_code
├── order_id → orders
├── order_item_id → order_items
├── user_id → users
├── quantity
├── status (reserved, fulfilled, cancelled)
├── observations
├── document
├── created_at
└── updated_at

inventory_pending (Pendências)
├── id
├── warehouse_id → warehouses
├── part_id → parts
├── part_code
├── service_order_id → service_orders
├── transaction_id → inventory_transactions
├── quantity
├── status (pending, fulfilled, cancelled)
├── observations
├── created_at
└── updated_at
```

---

### 11. Notas Fiscais (Invoices)

```
invoices (Notas Fiscais)
├── id
├── tenant_id → tenants
├── invoice_number
├── series
├── invoice_type (entrada, saida)
├── purpose (normal, complementar, devolucao)
├── operation_nature
├──
├── # Emitente
├── issuer_document
├── issuer_name
├── issuer_trade_name
├── issuer_address
├── issuer_address_number
├── issuer_neighborhood
├── issuer_city
├── issuer_city_code
├── issuer_state
├── issuer_postal_code
├── issuer_country
├── issuer_country_code
├── issuer_phone
├── issuer_state_registration
├── issuer_tax_regime
├──
├── # Destinatário
├── recipient_document
├── recipient_name
├── recipient_address
├── recipient_address_number
├── recipient_neighborhood
├── recipient_city
├── recipient_city_code
├── recipient_state
├── recipient_postal_code
├── recipient_country
├── recipient_country_code
├── recipient_phone
├── recipient_state_registration
├── recipient_ie_indicator
├──
├── # Valores e Impostos
├── products_total (decimal 12,2)
├── freight_value (decimal 12,2)
├── insurance_value (decimal 12,2)
├── discount_value (decimal 12,2)
├── other_expenses (decimal 12,2)
├── invoice_total (decimal 12,2)
├── icms_base (decimal 12,2)
├── icms_value (decimal 12,2)
├── icms_st_base (decimal 12,2)
├── icms_st_value (decimal 12,2)
├── ipi_value (decimal 12,2)
├── pis_value (decimal 12,2)
├── cofins_value (decimal 12,2)
├──
├── # Controle
├── invoice_key
├── status
├── reason
├── additional_info
├── issue_date
├── exit_entry_date
├── receipt_date
├── is_stock_updated (bool)
├── brand_id → brands
├──
├── # Referências
├── referenced_invoices (JSON)
├──
├── created_at
└── updated_at

invoice_items (Itens da NF)
├── id
├── invoice_id → invoices
├── product_code
├── ean
├── product_name
├── ncm
├── cfop
├── cest
├── unit
├── quantity
├── unit_price (decimal 12,4)
├── total_price (decimal 12,2)
├──
├── # Tributação
├── icms_origin
├── icms_cst
├── icms_base_mode
├── icms_base (decimal 12,2)
├── icms_rate (decimal 5,2)
├── icms_value (decimal 12,2)
├── ipi_cst
├── ipi_value (decimal 12,2)
├── pis_cst
├── pis_base (decimal 12,2)
├── pis_rate (decimal 5,4)
├── pis_value (decimal 12,2)
├── cofins_cst
├── cofins_base (decimal 12,2)
├── cofins_rate (decimal 5,4)
├── cofins_value (decimal 12,2)
├──
├── created_at
└── updated_at

invoice_comments (Acompanhamento NF)
├── id
├── invoice_id → invoices
├── order_id → orders
├── service_order_id → service_orders
├── is_bound (bool)
├── event
├── status
├── colors
├── icon
├── part_code
├── created_at
└── updated_at
```

---

### 12. Fechamento Mensal (Monthly Closing)

```
monthly_closings (Fechamento Mensal)
├── id
├── tenant_id → tenants
├── reference_month (YYYY-MM)
├── partner_id → partners
├── partner_code
├── partner_document
├── partner_name
├──
├── # Valores
├── total_value (decimal 12,2)
├──
├── # NF do Posto
├── has_invoice_uploaded (bool)
├── invoice_number
├── invoice_files (JSON)
├──
├── # Status Financeiro
├── financial_status (pending, approved, rejected, paid)
├── rejection_reason
├──
├── # Alteração de NF
├── invoice_change_reason
├── invoice_changed_at
├── previous_invoice_number
├──
├── # Pagamento
├── payment_forecast_date
├── paid_at
├──
├── # Manifestação
├── manifestation_notes
├── manifestation_status (pending, approved, rejected)
├──
├── created_at
└── updated_at

monthly_closing_items (OS do Fechamento)
├── id
├── monthly_closing_id → monthly_closings
├── service_order_id → service_orders
├── partner_code
├── closed_at
├── protocol
├── total_value (decimal 12,2)
├── is_consolidated (bool)
├── payment_forecast_date
├── created_at
└── updated_at

monthly_closing_audits (Auditoria)
├── id
├── monthly_closing_id → monthly_closings
├── user_id → users
├── action
├── field_name
├── old_value
├── new_value
├── description
├── created_at
└── updated_at
```

---

### 13. Call Center

```
call_center_tickets (Chamados)
├── id
├── tenant_id → tenants
├── ticket_number
├──
├── # Relacionamentos
├── customer_id → customers
├── service_order_id → service_orders
├── partner_id → partners
├──
├── # Classificação
├── channel (phone, email, chat, whatsapp)
├── priority (low, medium, high, critical)
├── category_id → ticket_categories
├──
├── # Status
├── status (open, in_progress, waiting, resolved, closed)
├──
├── # Conteúdo
├── subject
├── description
├── resolution
├──
├── # Datas
├── opened_at
├── opened_by → users
├── assigned_to → users
├── first_response_at
├── resolved_at
├── resolved_by → users
├── closed_at
├──
├── created_at
└── updated_at

call_center_ticket_comments
├── id
├── ticket_id → call_center_tickets
├── user_id → users
├── comment
├── is_internal (bool)
├── created_at
└── updated_at

ticket_categories
├── id
├── name
├── description
├── is_active
├── created_at
└── updated_at
```

---

### 14. Transportadoras (Shipping)

```
shipping_companies (Transportadoras)
├── id
├── tenant_id → tenants
├── name
├── document
├── state_registration
├── address
├── address_number
├── address_complement
├── neighborhood
├── city
├── state
├── postal_code
├── supplier_code
├── is_active
├── created_at
└── updated_at

shipments (Envios)
├── id
├── shipping_company_id → shipping_companies
├── order_id → orders
├── service_order_id → service_orders
├── tracking_code
├── invoice_number
├── invoice_date
├── shipped_at
├── estimated_delivery_date
├── delivered_at
├── status
├── observations
├── created_at
└── updated_at

shipment_events (Eventos de Rastreamento)
├── id
├── shipment_id → shipments
├── event_date
├── event_description
├── location
├── status
├── created_at
└── updated_at
```

---

### 15. Usuários e Permissões (Users & ACL)

```
users (Usuários)
├── id
├── tenant_id → tenants (null = super admin)
├── partner_id → partners (null = não é posto)
├── name
├── email
├── username
├── password
├── avatar_path
├── is_partner_user (bool)
├── is_test_user (bool)
├── is_active
├── email_verified_at
├── remember_token
├── last_login_at
├── created_at
├── updated_at
└── deleted_at

roles (Papéis)
├── id
├── name
├── description
├── is_system (bool - não pode ser deletado)
├── created_at
└── updated_at

permissions (Permissões)
├── id
├── name
├── description
├── module
├── created_at
└── updated_at

teams (Times/Departamentos)
├── id
├── tenant_id → tenants
├── name
├── description
├── created_at
└── updated_at

# Pivots
role_user
├── id
├── role_id → roles
├── user_id → users
├── created_at
└── updated_at

permission_role
├── id
├── permission_id → permissions
├── role_id → roles
├── created_at
└── updated_at

permission_user
├── id
├── permission_id → permissions
├── user_id → users
├── created_at
└── updated_at

team_user
├── id
├── team_id → teams
├── user_id → users
├── created_at
└── updated_at

role_team
├── id
├── role_id → roles
├── team_id → teams
├── created_at
└── updated_at

permission_team
├── id
├── permission_id → permissions
├── team_id → teams
├── created_at
└── updated_at
```

---

### 16. Tabelas de Remuneração (Pricing)

```
service_pricing (Tabela de Preços por Serviço)
├── id
├── tenant_id → tenants
├── brand_id → brands
├── product_category_id → product_categories
├── service_type_id → service_types
├── service_location_id → service_locations
├── base_value (decimal 12,2)
├── is_active
├── valid_from
├── valid_until
├── created_at
└── updated_at

cost_types (Tipos de Custo Adicional)
├── id
├── tenant_id → tenants
├── name
├── product_type
├── is_fixed_cost (bool)
├── is_fixed_unit (bool)
├── is_default (bool)
├── requires_approval (bool)
├──
├── # Valores por Marca
├── lg_value (decimal 12,2)
├── tcl_value (decimal 12,2)
├── britania_value (decimal 12,2)
├── efl_value (decimal 12,2)
├── default_value (decimal 12,2)
├──
├── created_by → users
├── updated_by → users
├── created_at
└── updated_at
```

---

### 17. Integrações (Integrations)

```
integration_tokens (Tokens de Integração)
├── id
├── tenant_id → tenants
├── provider (bling, gateway_nf, etc)
├── client_id
├── client_secret
├── access_token
├── refresh_token
├── authorization
├── expires_at
├── created_at
└── updated_at

integration_logs (Logs de Integração)
├── id
├── tenant_id → tenants
├── provider
├── direction (inbound, outbound)
├── endpoint
├── method
├── request_data (JSON)
├── response_data (JSON)
├── status_code
├── error_message
├── created_at
└── updated_at
```

---

### 18. Importações (Imports)

```
import_batches (Lotes de Importação)
├── id
├── tenant_id → tenants
├── user_id → users
├── import_type (service_orders, parts, partners)
├── file_name
├── file_path
├── status (pending, processing, completed, failed)
├── total_rows
├── processed_rows
├── success_rows
├── error_rows
├── started_at
├── completed_at
├── created_at
└── updated_at

import_rows (Linhas da Importação)
├── id
├── import_batch_id → import_batches
├── row_number
├── data (JSON)
├── status (pending, success, error)
├── error_message
├── created_entity_type
├── created_entity_id
├── created_at
└── updated_at
```

---

### 19. Lookup Tables (Tabelas Auxiliares)

```
service_order_statuses
├── id, code, name, color, icon, display_order, is_active

service_order_types
├── id, code, name, color, display_order, is_active

service_types
├── id, code, name, color, display_order, is_active

service_locations
├── id, code, name, color, display_order, is_active

repair_types
├── id, code, name, color, is_active

tracking_statuses
├── id, code, name, color, is_active

accept_statuses
├── id, name, text_color, bg_color, icon

invite_statuses
├── id, name, color, icon

schedule_statuses
├── id, name, color, icon

order_statuses
├── id, name, description, color, icon, alias

closing_types
├── id, name, description

evidence_types
├── id, name, file_name_pattern, is_mandatory, applies_to (os, exchange)

comment_privacies
├── id, name, description, color, icon, is_default

document_types
├── id, type, description

transaction_types
├── id, type, description, operation (in, out, transfer)

contact_types
├── id, name

company_types
├── id, name

tax_regimes
├── id, name, code

pix_key_types
├── id, name

states
├── id, code, name, ibge_code

postal_codes
├── id, postal_code, postal_code_range, state, city, address, complement, neighborhood
```

---

### 20. Sistema Laravel

```
# Padrão Laravel
sessions
password_reset_tokens
personal_access_tokens
cache
cache_locks
jobs
job_batches
failed_jobs
notifications
```

---

## 📊 Mapeamento: Tabela Antiga → Nova

| Tabela Antiga              | Tabela Nova                       | Observações                      |
| -------------------------- | --------------------------------- | -------------------------------- |
| `os`                       | `service_orders`                  | Renomear campos                  |
| `os_follow`                | -                                 | REMOVER (duplicada)              |
| `os_follows`               | `service_order_comments`          | -                                |
| `os_parts`                 | `service_order_parts`             | -                                |
| `os_costs`                 | `service_order_costs`             | -                                |
| `os_evidence_files`        | `service_order_evidence_files`    | -                                |
| `os_technical_support`     | `service_order_technical_support` | -                                |
| `os_adm_support`           | `service_order_admin_support`     | -                                |
| `os_invites`               | `service_order_invites`           | -                                |
| `os_schedules`             | `service_order_schedules`         | -                                |
| `os_changes`               | `service_order_changes`           | -                                |
| `os_closings`              | `monthly_closing_items`           | -                                |
| `os_closing_consolidateds` | `monthly_closings`                | -                                |
| `clifor`                   | `customers`                       | -                                |
| `clifor_changes`           | `customer_changes`                | -                                |
| `partners`                 | `partners`                        | Ajustar campos                   |
| `contacts`                 | `partner_contacts`                | -                                |
| `parts`                    | `parts`                           | Ajustar tipos                    |
| `itemlocs`                 | `inventory_items`                 | -                                |
| `itemtrans`                | `inventory_transactions`          | -                                |
| `itemres`                  | `inventory_reserves`              | -                                |
| `itempend`                 | `inventory_pending`               | -                                |
| `warehouses`               | `warehouses`                      | -                                |
| `orders`                   | `orders`                          | -                                |
| `orders_items`             | `order_items`                     | -                                |
| `order_follows`            | `order_comments`                  | -                                |
| `orders_nfs`               | `order_invoices`                  | -                                |
| `orders_statuses`          | `order_statuses`                  | -                                |
| `exchanges`                | `exchanges`                       | -                                |
| `ex_follows`               | `exchange_comments`               | -                                |
| `ex_evidence_files`        | `exchange_evidence_files`         | -                                |
| `ex_statuses`              | `exchange_statuses`               | -                                |
| `fiscal_invoices`          | `invoices`                        | -                                |
| `fiscal_invoice_items`     | `invoice_items`                   | -                                |
| `nfs`                      | -                                 | REMOVER (duplicada)              |
| `brands`                   | `brands`                          | -                                |
| `product_models`           | `product_models`                  | -                                |
| `product_types`            | `product_categories`              | -                                |
| `fornecedores`             | -                                 | REMOVER (usar manufacturers)     |
| `tipodocumento`            | -                                 | REMOVER (usar document_types)    |
| `tipotrans`                | -                                 | REMOVER (usar transaction_types) |
| `tipores`                  | -                                 | REMOVER (legado)                 |
| `ceps`                     | `postal_codes`                    | -                                |
| `ufs`                      | `states`                          | -                                |
| `bling_tokens`             | `integration_tokens`              | Generalizar                      |
| `service_order_invites`    | -                                 | REMOVER (duplicada)              |
| `service_order_schedules`  | -                                 | REMOVER (duplicada)              |

---

## 📝 Próximos Passos

1. [ ] Validar estrutura proposta
2. [ ] Definir prioridade dos módulos
3. [ ] Criar migrations Laravel
4. [ ] Criar Models com relacionamentos
5. [ ] Criar Factories e Seeders
6. [ ] Desenvolver scripts de migração de dados
7. [ ] Testar em ambiente de staging
8. [ ] Executar migração em produção

---

## ✏️ Notas e Decisões

_Adicione aqui observações e decisões tomadas durante o desenvolvimento_
