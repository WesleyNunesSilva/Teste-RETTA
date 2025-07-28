<?php

namespace App\Jobs;

use App\Models\Deputy;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDeputyExpensesFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deputyId;
    public $page;

    public function __construct($deputyId, $page = 1)
    {
        $this->deputyId = $deputyId;
        $this->page = $page;

        $this->onQueue('expenses'); // Define a fila
    }

    public function handle(): void
    {
        try {
            $deputy = Deputy::find($this->deputyId);

            if (!$deputy) {
                Log::warning("Deputado não encontrado: ID {$this->deputyId}");
                return;
            }

            $response = Http::timeout(60)->get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$deputy->external_id}/despesas", [
                'pagina' => $this->page,
                'itens' => 100,
                'ordem' => 'ASC',
            ]);

            if ($response->failed()) {
                Log::error("Erro ao buscar despesas do deputado {$deputy->name}", [
                    'page' => $this->page,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return;
            }

            $expenses = $response->json('dados');
            $links = $response->json('links');

            if (empty($expenses)) {
                Log::info("Nenhuma despesa encontrada para deputado {$deputy->name}, página {$this->page}");
                return;
            }

            foreach ($expenses as $data) {
                if (empty($data['codDocumento'])) {
                    continue; // Ignora registros sem ID
                }

                Expense::updateOrCreate(
                    [
                        'external_id' => $data['codDocumento'],
                        'deputy_id' => $deputy->id
                    ],
                    [
                        'year' => $data['ano'],
                        'month' => $data['mes'],
                        'expense_type' => $data['tipoDespesa'],
                        'amount' => $this->parseAmount($data['valorLiquido'] ?? $data['valorDocumento']),
                        'supplier_name' => $data['nomeFornecedor'] ?? 'Não informado',
                        'document_url' => $data['urlDocumento'] ?? null
                    ]
                );
            }

            // Próxima página, se houver
            if ($this->hasNextPage($links)) {
                self::dispatch($this->deputyId, $this->page + 1)
                    ->onQueue('expenses')
                    ->delay(now()->addSeconds(3));
            }

            // Atualiza a flag no deputado
            $deputy->update(['last_synced_expenses_at' => now()]);
        } catch (\Exception $e) {
            Log::error("Erro crítico ao sincronizar despesas", [
                'deputy_id' => $this->deputyId,
                'page' => $this->page,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function parseAmount($value): float
    {
        if (is_numeric($value)) return (float) $value;
        return (float) str_replace(',', '.', preg_replace(['/R\$\s*/', '/\./'], '', $value));
    }

    private function hasNextPage(array $links): bool
    {
        foreach ($links as $link) {
            if ($link['rel'] === 'next') return true;
        }
        return false;
    }
}