<?php

namespace Source\App;

use Source\Models\CashFlow;
use Source\Models\Moviment;
use Source\Support\Pager;
use Source\Support\SeoBuilder;

/**
 *
 */
class Query extends App
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * RESPONSAVEL POR RENDERIZAR OS PREMIOS PAGOS
     * @param array $data
     * @return void
     */
    public function paidPrizes(array $data): void
    {
        $head = $this->seo->make('Premios Pagos - ', url());
        $fixed = (empty($data['cost']) || $data['cost'] == 'all' ? "(id_cost = 17 OR id_cost = 4 OR id_cost = 18) AND" : '');

        echo $this->view->render('querys/paid-prizes',
            [
                'head' => $head,
                'prizes' => (new CashFlow())->filterQuery($data, "{$fixed} type = 2"),
                'filter' => (object)[
                    "store" => ($data["store"] ?? null),
                    "cost" => ($data["cost"] ?? null),
                    "hour" => ($data["hour"] ?? null),
                    "date" => ($data["date"] ?? null)
                ]
            ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function attachIncome(array $data): void
    {
        $head = $this->seo->make('Premios Pagos - ', url());

        echo $this->view->render('');
    }

    /**
     * @param array $data
     * @return void
     */
    public function attachExpense(array $data): void
    {
        $head = $this->seo->make('Premios Pagos - ', url());

        echo $this->view->render('');
    }


    /**
     * RESPONSÁVEL POR SETAR OS FILTROS VIA POST
     * @param array $data
     * @return void
     */
    public function filters(array $data): void
    {
        $dataObject = new \stdClass();
        $dataObject->route = ($data['route'] ?? '');

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        if (!empty($data['date'])) {
            $dateApp = ($data['date'] != 'all' ? date_fmt($data['date'], 'Y-m-d') : $data['date']);
        }

        if (!empty($data['search_date'])) {
            $dateApp = ($data['search_date'] != 'all' ? date_fmt($data['search_date'], 'Y-m-d') : $data['search_date']);
        }

        $dataObject->date_moviment = (!empty($data['search_date']) ? $dateApp : 'all');

        $dataObject->cost = (!empty($data['cost']) && filter_var($data['cost'], FILTER_VALIDATE_INT) ? $data['cost'] : 'all');
        $dataObject->date = (!empty($data['date']) ? $dateApp : 'all');
        $dataObject->store = (!empty($data['store']) && filter_var($data['store'], FILTER_VALIDATE_INT) ? $data['store'] : 'all');
        $dataObject->hour = (!empty($data['hour']) ? $data['hour'] : 'all');

        // ESCOLHE UMA ROTA E REDIRECIONA
        echo Query::chooseRoute($dataObject);
    }

    /**
     * @param array $data
     * @return void
     */
    public function storeBalance(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Movimentação - ", url('/app/movimentacoes'));

        // QUERYS
        $data['search_store'] = (!empty($data['search_store']) && $data['search_store'] != 'all' ? "AND id_store = {$data['search_store']}" : null);
        $data['search_hour'] = (!empty($data['search_hour']) && $data['search_hour'] != 'all' ? "AND id_hour = {$data['search_hour']}" : null);
        $data['search_date'] = (!empty($data['search_date']) && $data['search_date'] != 'all' ? "AND DATE(date_moviment) = DATE('{$data['search_date']}')" : null);


        list($moviments, $search, $total) = (new Moviment())->filter($data);

        echo $this->view->render('store-balance', [
            'head' => $head,
            'allMoney' => isnt_empty($total, 'self', '0.00'),
            'search' => (object)$search
        ]);
    }


    /**
     * RESPONSÁVEL POR ESCOLHER A ROTA DE RETORNO DO FILTRO
     * @param \stdClass $data
     * @return string
     */
    public static function chooseRoute(\stdClass $data): string
    {
        // VERIFICA A ROTA E REDIRECIONA
        switch ($data->route) {
            case 'premios-pagos':
                $json['redirect'] = url("/consultas/{$data->route}/{$data->cost}/{$data->date}/{$data->store}/$data->hour}");
                break;
            case 'consultar-saldo-da-loja':
                $json['redirect'] = url("/consultas/{$data->route}/{$data->date_moviment}/{$data->store}/{$data->hour}");
                break;
            default:
                $json['redirect'] = url();
                break;
        }

        return json_encode($json);
    }

}