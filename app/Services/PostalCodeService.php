<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PostalCode;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostalCodeService
{
    private const VIACEP_URL = 'https://viacep.com.br/ws';

    /**
     * Busca endereço pelo CEP.
     * Primeiro tenta no banco local, se não encontrar consulta a API ViaCep.
     *
     * @return array<string, mixed>|null
     */
    public function findByCode(string $code): ?array
    {
        // Remove formatação do CEP (traço, espaços)
        $code = $this->sanitizeCode($code);

        if (! $this->isValidCode($code)) {
            return null;
        }

        // Tenta encontrar no banco local
        $postalCode = $this->findInDatabase($code);

        if ($postalCode instanceof PostalCode) {
            return $this->formatResponse($postalCode);
        }

        // Se não encontrou, busca na API ViaCep
        $viaCepData = $this->fetchFromViaCep($code);

        if ($viaCepData) {
            // Salva no banco local para cache
            $postalCode = $this->saveToDatabase($viaCepData);

            return $this->formatResponse($postalCode);
        }

        return null;
    }

    /**
     * Remove formatação do CEP.
     */
    public function sanitizeCode(string $code): string
    {
        return preg_replace('/\D/', '', $code) ?? '';
    }

    /**
     * Valida se o CEP tem formato válido.
     */
    public function isValidCode(string $code): bool
    {
        $sanitized = $this->sanitizeCode($code);

        return strlen($sanitized) === 8 && ctype_digit($sanitized);
    }

    /**
     * Busca CEP no banco local.
     */
    private function findInDatabase(string $code): ?PostalCode
    {
        // Busca exata primeiro
        $postalCode = PostalCode::where('code', $code)->first();

        if ($postalCode) {
            return $postalCode;
        }

        // Se não encontrou, tenta com zeros à esquerda removidos
        $codeWithoutLeadingZeros = ltrim($code, '0') ?: '0';

        return PostalCode::where('code', $codeWithoutLeadingZeros)->first();
    }

    /**
     * Busca CEP na API ViaCep.
     *
     * @return array<string, mixed>|null
     */
    private function fetchFromViaCep(string $code): ?array
    {
        try {
            $response = Http::timeout(5)
                ->get(self::VIACEP_URL."/{$code}/json");

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            // ViaCep retorna {"erro": true} quando CEP não existe
            if (isset($data['erro']) && $data['erro'] === true) {
                return null;
            }

            return $data;
        } catch (Exception $e) {
            Log::warning('ViaCep API error', [
                'code' => $code,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Salva dados do ViaCep no banco local.
     *
     * @param  array<string, mixed>  $viaCepData
     */
    private function saveToDatabase(array $viaCepData): PostalCode
    {
        $code = $this->sanitizeCode($viaCepData['cep'] ?? '');

        return PostalCode::updateOrCreate(
            ['code' => $code],
            [
                'state' => $viaCepData['uf'] ?? null,
                'city' => $viaCepData['localidade'] ?? null,
                'street' => $viaCepData['logradouro'] ?? null,
                'neighborhood' => $viaCepData['bairro'] ?? null,
                'complement' => $viaCepData['complemento'] ?? null,
            ],
        );
    }

    /**
     * Formata resposta padrão.
     *
     * @return array<string, mixed>
     */
    private function formatResponse(PostalCode $postalCode): array
    {
        return [
            'code' => $postalCode->code,
            'formatted_code' => $postalCode->formatted_code,
            'street' => $postalCode->street,
            'neighborhood' => $postalCode->neighborhood,
            'city' => $postalCode->city,
            'state' => $postalCode->state,
            'complement' => $postalCode->complement,
        ];
    }
}
