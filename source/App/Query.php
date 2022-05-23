<?php

namespace Source\App;

use Source\Models\CashFlow;
use Source\Models\Moviment;
use Source\Support\Pager;
use Source\Support\SeoBuilder;

class Query extends App
{
    public function __construct()
    {
        parent::__construct();
    }

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

    public function attachIncome(array $data): void
    {
        $head = $this->seo->make('Premios Pagos - ', url());

        echo $this->view->render('');
    }

    public function attachExpense(array $data): void
    {
        $head = $this->seo->make('Premios Pagos - ', url());

        echo $this->view->render('');
    }


    public function filters(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $dateApp = ($data['date'] != 'all' ? date_fmt($data['date'], 'Y-m-d') : $data['date']);

        $cost = (!empty($data['cost']) && filter_var($data['cost'], FILTER_VALIDATE_INT) ? $data['cost'] : 'all');
        $date = (!empty($data['date']) ? $dateApp : 'all');
        $store = (!empty($data['store']) && filter_var($data['store'], FILTER_VALIDATE_INT) ? $data['store'] : 'all');
        $hour = (!empty($data['hour']) ? $data['hour'] : 'all');

        $json['redirect'] = url("/consultas/{$data['route']}/{$cost}/{$date}/{$store}/{$hour}");
        echo json_encode($json);
    }

    public function storeBalance(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Movimentação - ", url('/app/movimentacoes'));

        list($moviments, $search, $total) = (new Moviment())->filter($data);

        echo $this->view->render('store-balance', [
            'head' => $head,
            'allMoney' => isnt_empty($total, 'self', '0.00'),
            'search' => (object)$search
        ]);
    }

}