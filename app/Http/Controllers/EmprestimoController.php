<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;

use Illuminate\Http\Request;

use App\Repositories\ClienteRepository;
use App\Repositories\EmprestimoRepository;
use App\Repositories\GarantiaRepository;
use App\Repositories\ParcelaRepository;

use App\Enums\TipoGarantia;
use App\Enums\EmprestimoStatus;
use App\Enums\PagamentoStatus;

use App\Services\RiscoEmprestimoService;
use App\Services\CalculaValorTotalService;
use App\Services\ParcelaService;

use App\Http\Requests\CalculaRiscoRequest;
use App\Http\Requests\PagamentoRequest;
use App\Http\Requests\CriaEmprestimoRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isArray;

class EmprestimoController extends Controller
{
    public function __construct(
        private ClienteRepository $clienteRepository,
        private EmprestimoRepository $emprestimoRepository,
        private GarantiaRepository $garantiaRepository,
        private ParcelaRepository $parcelaRepository,
        private RiscoEmprestimoService $riscoEmprestimoService,
        private CalculaValorTotalService $calculaValorTotalService,
        private ParcelaService $parcelaService
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(
            'pages.dashboard',
            [
                'clientes' => $this->clienteRepository->getClientsByUser(),
                'emprestimoStatus' => EmprestimoStatus::preencherSelect()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = $this->clienteRepository->getClientsByUser();
        return view(
            'pages.cadastrar-emprestimo',
            ['clientes' => $clientes],
            ['garantia' => TipoGarantia::preencherSelect()]
        );
    }

    public function calcularRisco(CalculaRiscoRequest $request)
    {
        $renda = $this->clienteRepository->find($request->cliente_id)->renda;

        $resultado = $this->riscoEmprestimoService->avaliaRiscoEmprestimo($request->all(), $renda);
        return response()->json([
            'nivel_risco' => $resultado['nivel_risco'] ?? 'Indeterminado',
            'taxa_juros' => $resultado['taxa_juros'] ?? 0,
            'recomendacao' => $resultado['recomendacao'] ?? 'Análise não disponível',
            'detalhes' => $resultado['detalhes'] ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CriaEmprestimoRequest $request)
    {
        DB::beginTransaction();

        try {
            $valor = $request->valor;
            $qtdParcelas = $request->qtd_parcelas;
            $percentualJuros = $request->percentual_juros;
            $tipoJuros = $request->tipo_juros;

            $valorTotal = $this->calculaValorTotalService->calculaValorTotal(
                $valor,
                $qtdParcelas,
                $percentualJuros,
                $tipoJuros
            );

            $dadosEmprestimo = [
                'cliente_id' => $request->cliente_id,
                'valor_emprestado' => $valor,
                'parcelas' => $qtdParcelas,
                'tipo_juros' => $tipoJuros,
                'taxa_juros_mensal' => $percentualJuros,
                'data_contratacao' => $request->data_contratacao,
                'status' => EmprestimoStatus::ATIVO,
                'finalidade' => $request->finalidade,
                'valor_total' => $valorTotal,
                'user_id' => Auth::id(),
            ];

            $idEmprestimo = $this->emprestimoRepository->create($dadosEmprestimo);

            $parcelas = $this->parcelaService->prepararParcelas(
                $idEmprestimo,
                $valorTotal,
                $qtdParcelas,
                $request->data_vencimento_primeira_parcela
            );

            $this->parcelaRepository->createMany($parcelas);

            if ($request->garantia_tipo) {
                $dadosGarantia = [
                    'tipo' => $request->garantia_tipo,
                    'valor_avaliado' => $request->garantia_valor_avaliado,
                    'descricao' => $request->garantia_descricao,
                    'emprestimo_id' => $idEmprestimo
                ];

                $this->garantiaRepository->create($dadosGarantia);
            }

            DB::commit();

            return redirect()->route('pages.dashboard')
                ->with('success', 'Emprestimo cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar emprestimo. Por favor, tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $clientes = $this->clienteRepository->getClientsByUser();

        return view(
            'pages.pagamento',
            ['clientes' => $clientes]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PagamentoRequest $request)
    {
        try {

            $dadosParcela = [
                'status' => PagamentoStatus::PAGO,
                'data_pagamento' => today()->format('Y-m-d')
            ];

            if ($request->has('multa_atraso')) {
                $dadosParcela['multa_atraso'] = $request->multa_atraso;
            }

            $emprestimoQuitado  = $this->parcelaRepository->update(
                $request->parcela_id,
                $request->emprestimo_id,
                $dadosParcela
            );

            if ($emprestimoQuitado) {
                $dadosEmprestimo = [
                    'status' => EmprestimoStatus::QUITADO,
                    'data_quitacao' => today()->format('Y-m-d')
                ];

                $this->emprestimoRepository->update($request->emprestimo_id, $dadosEmprestimo);
            }

            return back()
                ->with('success', 'Pagamento efetuado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao efetuar pagamento. Por favor, tente novamente.');
        }
    }

    public function listaEmprestimos(Request $request)
    {
        $arrayClientes = $request->cliente_id;

        if (!is_array($arrayClientes)) {
            $arrayClientes = [$arrayClientes];
        }

        $emprestimos = $this->emprestimoRepository->getEmprestimoByClientes($arrayClientes, $request->somente_nao_quitados);

        return response()->json([
            'success' => true,
            'emprestimos' => $emprestimos
        ]);
    }

    public function destroy(Emprestimo $emprestimo)
    {
        //
    }
}
