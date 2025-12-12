<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PostalCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    public function __construct(
        private readonly PostalCodeService $postalCodeService,
    ) {}

    /**
     * Busca endereço pelo CEP.
     */
    public function show(Request $request, string $code): JsonResponse
    {
        $result = $this->postalCodeService->findByCode($code);

        if (! $result) {
            return response()->json([
                'success' => false,
                'message' => 'CEP não encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
