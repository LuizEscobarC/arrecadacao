<?php

namespace Source\App\Api;

use Source\Models\Store;

class StoreApi extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        $stores = (new Store())->find()->fetch(true);
        foreach ($stores as $store) {
            $data[] = $store->data();
        }
        $this->back(['stores' => $data]);
    }

    public function create(array $data): void
    {
        if (!$this->requestLimit('createStore', 3, 60)) {
            $this->call(
                400,
                'request_limit',
                'Aguarde 60 segundos para voltar a requisitar.'
            )->back();
            return;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $store = new store();

        $store->code = (!empty($data['code']) ? $data['code'] : null);
        $store->nome_loja = (!empty($data['nome_loja']) ? $data['nome_loja'] : null);
        $store->valor_saldo = (!empty($data['valor_saldo']) ? $data['valor_saldo'] : null);
        $store->comissao = (!empty($data['comissao']) ? $data['comissao'] : null);
        $store->valor_aluguel = (!empty($data['valor_aluguel']) ? $data['valor_aluguel'] : null);
        $store->aluguel_dia = (!empty($data['aluguel_dia']) ? $data['aluguel_dia'] : null);
        $store->valor_gratificacao = (!empty($data['valor_gratificacao']) ? $data['valor_gratificacao'] : null);
        $store->gratificacao_dia = (!empty($data['gratificacao_dia']) ? $data['gratificacao_dia'] : null);


        if (!$store->save()) {
            $this->call(
                400,
                'invalid_values',
                $store->message()->getText()
            )->back();
            return;
        }

        $this->back(['response' => $store->data()]);

    }

    public function read(array $data): void
    {
        if (empty($data['store_id']) || !filter_var($data['store_id'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'invalid_values',
                'A loja que tentou acessar não existe.',
            )->back();
            return;
        }
        $store = (new Store())->findById($data['store_id']);
        if (!$store) {
            $this->call(
                404,
                'not_found',
                'A loja que tentou acessar não existe.',
            )->back();
            return;
        }
        $this->back(['store' => $store->data()]);
    }

    public function update(array $data): void
    {
        if (!$this->requestLimit('updateStore', 3, 60)) {
            $this->call(
                400,
                'request_limit',
                'Aguarde 60 segundos para voltar a requisitar.'
            )->back();
            return;
        }

        if (empty($data['store_id']) || !filter_var($data['store_id'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'invalid_values',
                'A loja que tentou acessar não existe.',
            )->back();
            return;
        }

        if (!$store = (new Store())->findByIdStore($data['store_id'])) {
            $this->call(
                400,
                'invalid_values',
                'A loja que tentou atualizar não existe.',
            )->back();
            return;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $store->code = (!empty($data['code']) ? $data['code'] : $store->code);
        $store->nome_loja = (!empty($data['nome_loja']) ? $data['nome_loja'] : $store->nome_loja);
        $store->valor_saldo = (!empty($data['valor_saldo']) ? $data['valor_saldo'] : $store->valor_saldo);
        $store->comissao = (!empty($data['comissao']) ? $data['comissao'] : $store->comissao);
        $store->valor_aluguel = (!empty($data['valor_aluguel']) ? $data['valor_aluguel'] : $store->valor_aluguel);
        $store->aluguel_dia = (!empty($data['aluguel_dia']) ? $data['aluguel_dia'] : $store->aluguel_dia);
        $store->valor_gratificacao = (!empty($data['valor_gratificacao']) ? $data['valor_gratificacao'] : $store->valor_gratificacao);
        $store->gratificacao_dia = (!empty($data['gratificacao_dia']) ? $data['gratificacao_dia'] : $store->gratificacao_dia);

        if (!$store->save()) {
            $this->call(
                400,
                'invalid_values',
                $store->message()->getText()
            )->back();
            return;
        }

        $this->call(
            200,
            'updated',
            $store->message()->getText(),
            'success'
        )->back(['response' => $store->data()]);

    }

    public function delete(array $data): void
    {
        if (!$this->requestLimit('deleteStore', 3, 60)) {
            $this->call(
                400,
                'request_limit',
                'Aguarde 60 segundos para voltar a requisitar.'
            )->back();
            return;
        }

        if (empty($data['store_id']) || !filter_var($data['store_id'], FILTER_VALIDATE_INT) || !$store = (new Store())->findById($data['store_id'])) {
            $this->call(
                400,
                'invalid_values',
                'O usuário que tentou deletar é inexistente.'
            )->back();
            return;
        }

        if (!$store->destroy()) {
            $this->call(
                400,
                'bad_request',
                $store->message()->getText()
            )->back();
        }

        $this->call(
            200,
            'deleted',
            $store->message()->getText(),
            'success'
        )->back();
    }

}