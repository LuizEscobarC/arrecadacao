<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\CafeApp\AppInvoice;

/**
 * CLASSE DA TABELA CASH-FLOW
 */
class CashFlow extends Model
{
    /**
     * CONSTRUTOR
     */
    public function __construct()
    {
        parent::__construct('cash_flow', ['id', 'created_at', 'updated_at'],
            ['date_moviment', 'id_store', 'id_hour', 'description', 'value', 'type']);
    }

    /**
     * @param string $dateMoviment
     * @param int $idStore
     * @param int $idHour
     * @param string $description
     * @param string $value
     * @param int $type
     * @param int|null $idCost
     * @return $this
     */
    public function bootstrap(
        string $dateMoviment,
        int $idStore,
        int $idHour,
        string $description,
        string $value,
        int $type,
        ?string $idCost
    ): CashFlow {
        $this->date_moviment = $dateMoviment;
        $this->id_store = $idStore;
        $this->id_hour = $idHour;
        $this->description = $description;
        $this->value = $value;
        $this->type = $type;
        $this->id_cost = $idCost;
        return $this;
    }

    /**
     * RETURN HOUR FOREIGN KEY VALUES
     * @return Hour|null
     */
    public function hour(): ?Hour
    {
        if ($this->id_hour) {
            (new $this);
            return (new Hour())->findById($this->id_hour);
        }
        return null;
    }

    /**
     * RETURN STORE FOREIGN KEY VALUES
     * @return mixed|Model|null
     */
    public function store()
    {
        if ($this->id_store) {
            (new $this);
            return (new Store())->findById($this->id_store);
        }
        return null;
    }

    /**
     * RETURN COSTCENTER FOREIGN KEY VALUES
     * @return mixed|Model|null
     */
    public function cost()
    {
        if ($this->id_cost) {
            (new $this);
            return (new Center())->findById($this->id_cost);
        }
        return null;
    }

    public function listFilters(?array $data): array
    {
        $search['search_store'] = filter_var((!empty($data['search_store']) ? $data['search_store'] : null),
            FILTER_SANITIZE_STRIPPED);
        $search['search_hour'] = filter_var((!empty($data['search_hour']) ? $data['search_hour'] : null),
            FILTER_SANITIZE_STRIPPED);
        $search['search_date'] = filter_var((!empty($data['search_date']) ? $data['search_date'] : null),
            FILTER_SANITIZE_STRIPPED);

        if ($search['search_store'] || $search['search_hour'] || $search['search_date']) {
            if ($search['search_store']) {
                $where[] = "s.nome_loja LIKE '%{$search['search_store']}%'";
            } else {
                $search['search_store'] = null;
            }
            if ($search['search_hour']) {
                $where[] = "h.description LIKE '%{$search['search_hour']}%'";
            } else {
                $search['search_hour'] = null;
            }
            if ($search['search_date']) {
                $date = str_replace('/', '-', $search['search_date']);
                $date = date_fmt_app($date);
                $where[] = "lists.date_moviment = '{$date}'";
            } else {
                $search['search_date'] = null;
            }

            $where = implode(' AND ', $where);

            // para pegar o totalizador de valor dinâmico com filtro
            $total = clone $this->find(null, null,
                'cash_flow.*, h.week_day, h.number_day, h.description as hour, s.nome_loja, cc.description as cost, sum(value) as total')
                ->join('hour h', 'h.id', 'cash_flow.id_hour')
                ->join('loja s', 's.id', 'cash_flow.id_store')
                ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

            $total->putQuery($where, ' WHERE ');

            $this->find(null, null,
                'cash_flow.*, h.week_day, h.number_day, h.description as hour, s.nome_loja, cc.description as cost')
                ->join('hour h', 'h.id', 'cash_flow.id_hour')
                ->join('loja s', 's.id', 'cash_flow.id_store')
                ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

            $this->putQuery($where, ' WHERE ');

        } else {
            $total = clone $this->find(null, null, 'sum(value) as total')
                ->join('hour h', 'h.id', 'cash_flow.id_hour')
                ->join('loja s', 's.id', 'cash_flow.id_store')
                ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

            $this->find(null, null,
                'cash_flow.*, h.week_day, h.number_day, h.description as hour, s.nome_loja, cc.description as cost')
                ->join('hour h', 'h.id', 'cash_flow.id_hour')
                ->join('loja s', 's.id', 'cash_flow.id_store')
                ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

            $search['search_store'] = null;
            $search['search_hour'] = null;
            $search['search_date'] = null;
        }
        return [$this, $search, $total];
    }


    /**
     * @return object
     */
    public function chartData(): object
    {
        $dateChart = [];
        for ($month = -4; $month <= 0; $month++) {
            $dateChart[] = date("m/Y", strtotime("{$month}month"));
        }

        $chartData = new \stdClass();
        $chartData->date_moviment = "'" . implode("','", $dateChart) . "'";
        $chartData->expense = "0,0,0,0,0";
        $chartData->income = "0,0,0,0,0";

        $numberDays = (new \DateTime('now'))->format('d');

        $chart = $this
            ->find("created_at BETWEEN DATE(now() - INTERVAL $numberDays DAY) AND '2022-03-01' GROUP BY DATE(created_at)",
                null,
                "   DATE_FORMAT(created_at, '%d') AS due_date,
                    (SELECT SUM(value) FROM cash_flow WHERE type = 1 AND DATE_FORMAT(created_at, '%d') = due_date) AS income,
                    (SELECT SUM(value) FROM cash_flow WHERE type = 2 AND DATE_FORMAT(created_at, '%d') = due_date ) AS expense
                "
            )->fetch(true);


        if ($chart) {
            $chartCategories = [];
            $chartExpense = [];
            $chartIncome = [];
            foreach ($chart as $chartItem) {
                $chartCategories[] = $chartItem->due_date;

                    $chartExpense[] = $chartItem->expense;
                    $chartIncome[] = $chartItem->income;

            }



            $chartData->date_moviment = "'" . implode("','", $chartCategories) . "'";
            $chartData->expense = implode(",", array_map("abs", $chartExpense));
            $chartData->income = implode(",", array_map("abs", $chartIncome));
        }

        return $chartData;
    }
}