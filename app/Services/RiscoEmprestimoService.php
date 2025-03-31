<?php

namespace App\Services;

class RiscoEmprestimoService
{
    public function avaliaRiscoEmprestimo(array $data, $rendaMensal): array
    {

        $valorEmprestimo = (float)$data['valor'];
        $rendaMensal = (float)$rendaMensal;
        $qtdParcelas = (int)$data['qtd_parcelas'];
        $percentualJurosBase = (float)$data['percentual_juros'];
        $temGarantia = !empty($data['garantia_tipo']) && !empty($data['garantia_valor_avaliado']);
        $valorGarantia = $temGarantia ? (float)$data['garantia_valor_avaliado'] : 0;

        // 1. Cálculos fundamentais
        $valorParcela = $valorEmprestimo / $qtdParcelas;
        $comprometimentoRenda = ($valorParcela / $rendaMensal) * 100;
        $relacaoGarantia = $temGarantia ? ($valorGarantia / $valorEmprestimo) * 100 : 0;

        // 2. Fatores de risco ponderados (valores mais baixos para reduzir severidade)
        $fatores = [
            'comprometimento_renda' => $this->calcularPesoComprometimento($comprometimentoRenda),
            'prazo_emprestimo' => $this->calcularPesoPrazo($qtdParcelas),
            'tipo_juros' => $data['tipo_juros'] === 'composto' ? 1.1 : 1.0, // reduzi o impacto
            'garantia' => $this->calcularPesoGarantia($relacaoGarantia),
            'valor_emprestimo' => $this->calcularPesoValor($valorEmprestimo)
        ];

        // 3. Cálculo do score de risco (0-100) com média ponderada mais favorável
        $pesoTotal = array_sum($fatores);
        $scoreRisco = ($pesoTotal / count($fatores)) * 80;

        // 4. Determinação do nível de risco e taxa (critérios mais lenientes)
        $resultado = $this->determinarNivelRisco($scoreRisco, $percentualJurosBase);

        // 5. Formatar resposta
        return [
            'nivel_risco' => $resultado['nivel'],
            'taxa_juros' => number_format($resultado['taxa'], 2),
            'recomendacao' => $this->gerarRecomendacao($resultado['nivel'], $comprometimentoRenda, $temGarantia),
            'detalhes' => [
                'score_risco' => round($scoreRisco, 2),
                'comprometimento_renda' => round($comprometimentoRenda, 2) . '%',
                'relacao_garantia' => $temGarantia ? round($relacaoGarantia, 2) . '%' : 'Sem garantia'
            ]
        ];
    }

    // Métodos auxiliares com critérios mais brandos
    private function calcularPesoComprometimento(float $percentual): float
    {
        if ($percentual < 20) return 0.5;  // aumentei os limites
        if ($percentual < 40) return 1.0;
        if ($percentual < 60) return 1.3; // reduzi o peso
        return 1.6; // reduzi o peso máximo
    }

    private function calcularPesoPrazo(int $meses): float
    {
        if ($meses <= 24) return 1.0;  // aumentei o limite
        if ($meses <= 48) return 1.2;  // reduzi o peso
        return 1.4; // reduzi o peso máximo
    }

    private function calcularPesoGarantia(float $percentualCobertura): float
    {
        if ($percentualCobertura >= 100) return 0.7;  // reduzi os limites
        if ($percentualCobertura >= 60) return 0.9;
        if ($percentualCobertura > 0) return 1.1;
        return 1.3; // reduzi o peso sem garantia
    }

    private function calcularPesoValor(float $valor): float
    {
        if ($valor < 10000) return 0.8;  // aumentei os limites
        if ($valor < 30000) return 1.0;
        if ($valor < 75000) return 1.2;
        return 1.5; // reduzi o peso máximo
    }

    private function determinarNivelRisco(float $score, float $taxaBase): array
    {
        if ($score < 70) {  // aumentei os limites para baixo
            return ['nivel' => 'Baixo', 'taxa' => $taxaBase * 0.9]; // -10%
        } elseif ($score < 80) {  // aumentei os limites
            return ['nivel' => 'Moderado', 'taxa' => $taxaBase];
        } elseif ($score < 90) {  // aumentei os limites
            return ['nivel' => 'Elevado', 'taxa' => $taxaBase * 1.15]; // +15%
        } else {
            return ['nivel' => 'Alto', 'taxa' => $taxaBase * 1.3]; // reduzi para +30%
        }
    }

    private function gerarRecomendacao(string $nivel, float $comprometimento, bool $temGarantia): string
    {
        $recomendacoes = [
            'Baixo' => "Ótima condição ({$comprometimento}% da renda). Taxa preferencial.",
            'Moderado' => "Condição regular ({$comprometimento}% da renda)." .
                ($temGarantia ? '' : ' Sugerimos considerar garantia para melhorar condições.'),
            'Elevado' => "Condição requer atenção ({$comprometimento}% da renda)." .
                ($temGarantia ? ' Avaliar garantias.' : ' Recomendamos garantias.'),
            'Alto' => "Condição cuidadosa ({$comprometimento}% da renda)." .
                ($temGarantia ? ' Garantia recomendada.' : ' Garantia necessária.')
        ];

        return $recomendacoes[$nivel] ?? 'Análise recomendada.';
    }
}
