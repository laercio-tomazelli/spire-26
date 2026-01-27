# Spire UI - Documentação dos Componentes

Esta documentação detalha todos os componentes disponíveis no Spire UI, com exemplos de uso, propriedades e configurações.

## 📋 Índice

### 🎛️ Formulários & Entrada
- [Button](button.md) - Botões com variantes e estados
- [Input](input.md) - Campos de entrada com validação e máscaras
- [Textarea](textarea.md) - Campos de texto multilinha com auto-resize
- [Select](select.md) - Dropdowns customizáveis com busca
- [Checkbox](checkbox.md) - Caixas de seleção individuais e em grupo
- [Radio](radio.md) - Botões de rádio para seleção única

### 📊 Dados & Visualização
- [Table](table.md) - Tabelas com ordenação, busca e paginação
- [Modal](modal.md) - Janelas modais com variações

### 🎨 Interface & Navegação
- [Dropdown](dropdown.md) - Menus dropdown com posicionamento automático
- [Tooltip](tooltip.md) - Dicas contextuais ao passar o mouse
- [Toast](toast.md) - Notificações temporárias não-intrusivas

### ⚙️ Utilitários
- [Form Group](form-group.md) - Agrupamento estruturado para campos de formulário
- [Skeleton](skeleton.md) - Placeholders animados para estados de carregamento
- [Badge](badge.md) - Indicadores visuais para status e contadores
- [Icon](icon.md) - Ícones Heroicon com fácil integração

---

## 🚀 Guia Rápido

### Instalação Básica

1. **Instale as dependências:**
```bash
composer require spire/spire-ui
npm install
```

2. **Configure os assets:**
```javascript
// resources/js/app.js
import { SpireUI } from './spire/index';
window.SpireUI = SpireUI;
```

3. **Importe o CSS:**
```css
/* resources/css/app.css */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
@import './spire/spire.css';
```

4. **Use nos templates:**
```blade
<x-spire::button>Meu Botão</x-spire::button>
```

### Estrutura de Arquivos

```
resources/
├── js/spire/
│   ├── components/     # Componentes individuais
│   ├── utilities/      # Utilitários
│   ├── types/          # Definições TypeScript
│   └── core/           # Núcleo da biblioteca
├── views/components/   # Templates Blade
└── css/                # Estilos customizados
```

### Convenções

- **Prefixo**: Todos os componentes usam o prefixo `x-spire::`
- **Atributos**: Propriedades são passadas como atributos HTML
- **Eventos**: Eventos Alpine.js são suportados
- **Acessibilidade**: Todos os componentes seguem WCAG 2.1 AA
- **Responsividade**: Design mobile-first

---

## 🎯 Boas Práticas

### Performance
- Use lazy loading para componentes pesados
- Implemente virtualização para listas grandes
- Minimize re-renders com Alpine.js

### Acessibilidade
- Sempre forneça labels descritivos
- Use ARIA quando necessário
- Teste com leitores de tela

### UX/UI
- Mantenha consistência visual
- Use feedback visual para ações
- Implemente estados de loading

---

## 🆘 Suporte

- 📖 [Documentação Completa](https://spire-ui.dev)
- 💬 [Discord Community](https://discord.gg/spire-ui)
- 🐛 [GitHub Issues](https://github.com/spire-ui/spire-ui/issues)
- 📧 [Email Support](mailto:support@spire-ui.dev)