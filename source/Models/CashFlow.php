<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\MoldelInterfaces\FilterInterface;
use Source\Support\Filters\FiltersCashFlow;

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
            ['date_moviment', 'id_hour', 'value', 'type']);
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
        ?string $idStore,
        string $idHour,
        ?string $description,
        string $value,
        string $type,
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

    public function moviment()
    {
        if ($this->id_moviment) {
            return (new Moviment())->findById($this->id_moviment);
        }
        return null;
    }

    public function findByDateMoviment($idMoviment, int $type, int $cost): ?CashFlow
    {
        return $this->find("id_moviment = {$idMoviment} AND type = {$type} AND id_cost = {$cost}")->fetch();
    }

    public function filter(array $data): array
    {
        if (!empty($data['page'])) {
            array_pop($data);
        }

        $situation = null;

        if (!empty($data)) {
            if (!empty($data['search_date'])) {
                $date = str_replace('/', '-', $data['search_date']);
                $data['search_date'] = "DATE('" . date_fmt_app($date) . "')";
            }

            $situation = (!empty($data['store_situation']) ? ($data['store_situation'] == 1 ? 'positive' : 'negative') : null);

            $filters = $data;
        } else {
            $filters = ['search_store' => null, 'search_hour' => null, 'search_date' => null];
        }

        $filterClass = new FiltersCashFlow($this, $filters);

        $arrayFilterReturn = $filterClass->where([
            'search_store' => 'like',
            'search_hour' => 'like',
            'search_date' => 'equal',
        ],
            [
                'search_store' => 's.nome_loja',
                'search_hour' => 'h.description',
                'search_date' => 'DATE(cash_flow.date_moviment)'
            ])
            ->find([
                'cash_flow.*',
                'h.week_day',
                'h.number_day',
                'h.description as hour',
                's.nome_loja',
                'cc.description as cost'
            ]);

        $total = $filterClass->total([
            'value' => 'total_value'
        ], new CashFlow());
        return array_merge($arrayFilterReturn, [$total]);
    }

    public function balance(): object
    {
        $numberDays = (new \DateTime('now'))->format('d');
        $numberMonthNow = 1 + ((int)(new \DateTime('now'))->format('m'));
        $numberYearNow = (new \DateTime('now'))->format('Y');


        $expenses = (new CashFlow())->find("created_at BETWEEN DATE(now() - INTERVAL $numberDays DAY) AND '{$numberYearNow}-{$numberMonthNow}-01' AND type = 2",
            null, 'value, description, date_moviment, id')->limit('5')->fetch(true);

        $incomes = (new CashFlow())->find("created_at BETWEEN DATE(now() - INTERVAL $numberDays DAY) AND '{$numberYearNow}-{$numberMonthNow}-01' AND type = 1",
            null, 'value, description, date_moviment, id')->limit('5')->fetch(true);

        $totalBilling = (($totalIncomes = (new CashFlow())->find("created_at BETWEEN DATE(now() - INTERVAL $numberDays DAY) AND '{$numberYearNow}-{$numberMonthNow}-01' AND type = 1",
                null,
                "sum(value) as total")->fetch()->total) - ($totalExpenses = (new CashFlow())->find("created_at BETWEEN DATE(now() - INTERVAL $numberDays DAY) AND '{$numberYearNow}-{$numberMonthNow}-01' AND type = 2",
                null, "sum(value) as total")->fetch()->total));

        return (object)[
            'expenses' => $expenses,
            'incomes' => $incomes,
            'totalBilling' => $totalBilling,
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses
        ];
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

        // BEGIN BUILDA O IN () DE MESES DA QUERY
        $m = date_fmt('now', '-m');
        $buildIn = '';
        for ($i = 1; $i <= 31; $i++) {
            if ($i < 10 && $i !== 31) {
                $buildIn .= "0{$i}{$m}, ";
            } elseif ($i !== 31) {
                $buildIn .= "{$i}{$m}, ";
            }
            if ($i === 31) {
                $buildIn .= "{$i}{$m}";
            }
        }
        // END BUILD

        $chart = $this
            ->find("DATE_FORMAT(date_moviment, '%m-%d') in ({$buildIn})",
                null,
                "   distinct DATE_FORMAT(date_moviment, '%d/%m') AS date,
                    (SELECT SUM(value) FROM cash_flow WHERE type = 1 AND DATE_FORMAT(date_moviment, '%d/%m') = date) AS income,
                    (SELECT SUM(value) FROM cash_flow WHERE type = 2 AND DATE_FORMAT(date_moviment, '%d/%m') = date ) AS expense
                "
            )->order('date_moviment')->fetch(true);


        // BEGIN FORMATA EM STRING SEPARADO POR VIRGULAS PARA O GRÁFICO
        if ($chart) {
            $chartCategories = [];
            $chartExpense = [];
            $chartIncome = [];
            foreach ($chart as $chartItem) {
                $chartCategories[] = $chartItem->date;

                $chartExpense[] = $chartItem->expense;
                $chartIncome[] = $chartItem->income;

            }


            $chartData->date_moviment = "'" . implode("','", $chartCategories) . "'";
            $chartData->expense = implode(",", array_map("abs", $chartExpense));
            $chartData->income = implode(",", array_map("abs", $chartIncome));
        }
        // END FORMATA

        return $chartData;
    }

    public function filterQuery(array $filter, string $fixed, ?int $limit = null , bool $total = false)
    {

        // QUERYS
        $storeQuery = (!empty($filter['store']) && $filter['store'] != 'all' ? "AND id_store = {$filter['store']}" : null);
        $costQuery = (!empty($filter['cost']) && $filter['cost'] != 'all' ? "AND id_cost = {$filter['cost']}" : null);
        $hourQuery = (!empty($filter['hour']) && $filter['hour'] != 'all' ? "AND h.description like '%{$filter['hour']}%'" : null);
        $dateQuery = (!empty($filter['date']) && $filter['date'] != 'all' ? "AND DATE(date_moviment) = DATE('{$filter['date']}')" : null);

        // CASO PROCURE PELO NOME DO HORÁRIO É PRECISO FAZER UM JOIN
        if ($hourQuery) {
            $cashFlows = $this->find()
                ->join('hour h', 'cash_flow.id_hour', 'h.id');
            $cashFlows->putQuery("{$fixed} {$storeQuery} {$costQuery} {$hourQuery} {$dateQuery}");

        } else {
            $cashFlows = $this->find("{$fixed} {$storeQuery} {$costQuery} {$hourQuery} {$dateQuery}");
        }

        if ($total) {
            if ($hourQuery) {
                $cashFlows = $this->find(null, null, 'sum(cash_flow.value) as totalPrizeExpense')
                    ->join('hour h', 'cash_flow.id_hour', 'h.id');
                $cashFlows->putQuery("{$fixed} {$storeQuery} {$costQuery} {$hourQuery} {$dateQuery}");

            } else {
                $cashFlows = $this->find("{$fixed} {$storeQuery} {$costQuery} {$hourQuery} {$dateQuery}", null,
                    'sum(cash_flow.value) as totalPrizeExpense');
            }
            if ($limit) {
                $cashFlows->limit($limit);
            }
            return $cashFlows->fetch();
        }

        if ($limit) {
            $cashFlows->limit($limit);
        }
        return $cashFlows->fetch(true);
    }
}