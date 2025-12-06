#!/bin/sh
#
# Instala os git hooks do projeto
#

echo "🔧 Instalando git hooks..."

# Copia o pre-commit hook
cp scripts/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

echo "✅ Git hooks instalados com sucesso!"
echo ""
echo "O hook pre-commit irá verificar automaticamente:"
echo "  - Sintaxe PHP"
echo "  - Estilo de código (Pint)"
echo "  - Testes (Pest)"
echo ""
echo "Para pular as verificações (não recomendado):"
echo "  git commit --no-verify"
