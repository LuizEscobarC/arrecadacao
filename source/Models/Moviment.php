<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Support\Filters\FilterMoviment;

class Moviment extends Model
{
    public function __construct()
    {
        parent::__construct('moviment', ['created_at', 'id', 'updated_at'],
            [
                'id_hour',
                'id_store',
                'date_moviment',
                'beat_value',
                'last_value',
                'get_value',
                'new_value'
            ]);
    }

    public function bootstrap
    (
        string $dateMoviment,
        string $idStore,
        string $idHour,
        ?string $idList,
        string $beatValue,
        string $payingNow,
        string $expend,
        string $lastValue,
        string $getValue,
        string $newValue,
        ?string $prize,
        ?string $beatPrize,
        ?string $prizeStore,
        ?string $prizeOffice
    ) {
        $this->date_moviment = $dateMoviment;
        $this->id_store = $idStore;
        $this->id_hour = $idHour;
        $this->id_list = $idList;
        $this->beat_value = $beatValue;
        $this->paying_now = $payingNow;
        $this->expend = $expend;
        $this->last_value = $lastValue;
        $this->get_value = $getValue;
        $this->new_value = $newValue;
        $this->prize = $prize;
        $this->beat_prize = $beatPrize;
        $this->prize_store = $prizeStore;
        $this->prize_office = $prizeOffice;
        return $this;
    }

    public function hour(int $hour = null): ?Hour
    {
        if (!empty($hour)) {
            return (new Hour())->findById($hour);
        } elseif ($this->id_hour) {
            return (new Hour())->findById($this->id_hour);
        }
        return null;
    }

    public function lists(int $list = null): ?Lists
    {
        if (!empty($list)) {
            return (new Lists())->findById($list);
        } elseif ($this->id_list) {
            return (new Lists())->findById($this->id_list);
        }
        return null;
    }

    public function store(int $store = null): ?Store
    {
        if (!empty($store)) {
            return (new Store())->findById($store);
        } elseif ($this->id_store) {
            return (new Store())->findById($this->id_store);
        }
        return null;
    }

    public function attach(array $data, User $user): bool
    {

        $modelVerify = new Moviment();
        $required = $modelVerify->requiredMoviment($data);
        if (!empty($required)) {
            $json['message'] = $required;
            echo json_encode($json);
            return false;
        }
        // referencia
        $modelVerify->isEmpty($data);

        // Se existir um movimento com a mesma data, horario e loja
        // se retornar falso entra aqui
        if (!$modelVerify->isRepeated($data['date_moviment'], $data['id_hour'], $data['id_store'])) {
            $json['message'] = $this->message->warning('O lançamento já existe.')->render();
            $json['redirect'] = url('app/cadastrar-movimentacao');
            $json['timeout'] = 3000;
            $json['scroll'] = 225;
            echo json_encode($json);
            return false;
        }

        $messageError = '';
        $store = (new Store());
        if (!empty($data['id_store'])) {
            $store = $store->findById($data['id_store']);
        }
        if (!empty($data['beat_prize'])) {
            $store->valor_saldo = (money_fmt_app($data['new_value']) + money_fmt_app($data['prize']));
        } else {
            $store->valor_saldo = money_fmt_app($data['new_value']);
        }

        if (!$store->save()) {
            $messageError .= ",  " . $store->message()->getText();
        }

        if (!empty($data['prize_office']) && !($data['prize_office'] === 0 || $data['prize_office'] === '0')) {
            $cash = (new CashFlow());

            // PREMIO ESCRITÓRIO DESPESA
            $cash->bootstrap(
                $data["date_moviment"],
                $data["id_store"],
                $data["id_hour"],
                'Saída de Premio do Escritório',
                money_fmt_app($data['prize_office']),
                2,
                17
            );

            if (!$cash->save()) {
                $messageError = $cash->message()->getText();
            }
        }

        if (!empty($data['prize_store']) && !($data['prize_store'] === 0 || $data['prize_store'] === '0')) {
            $cash = (new CashFlow());

            // PREMIO LOJA PAGOU POREM É DESPESA
            $cash->bootstrap(
                $data["date_moviment"],
                $data["id_store"],
                $data["id_hour"],
                'Abate de Premio da loja ' . $store->nome_loja,
                money_fmt_app($data['prize_store']),
                2,
                4
            );

            if (!$cash->save()) {
                $messageError = $cash->message()->getText();
            }
        }

        if (money_fmt_app($data['get_value']) && !(money_fmt_app($data['get_value']) === 0 || money_fmt_app($data['get_value']) === '0')) {

            $cash = (new CashFlow());

            // VALOR RECOLHIDO DA LOJA
            $cash->bootstrap(
                $data["date_moviment"],
                $data["id_store"],
                $data["id_hour"],
                ($list->description ?? '') . ' Entrada de ' . ($store->nome_loja ?? 'loja'),
                money_fmt_app($data["get_value"]),
                1,
                16
            );

            if (!$cash->save()) {
                $messageError = $cash->message()->getText();
            }

            // DESPESAS DA LOJA
            if (money_fmt_app($data['expend']) && !(money_fmt_app($data['expend']) == 0 || money_fmt_app($data['expend']) == '0')) {
                $cash = (new CashFlow());
                $cash->bootstrap(
                    $data["date_moviment"],
                    $data["id_store"],
                    $data["id_hour"],
                    ' A ' . ($store->nome_loja ?? 'loja') . ' teve uma despesa',
                    money_fmt_app($data["expend"]),
                    2,
                    2
                );
                if (!$cash->save()) {
                    $messageError = $cash->message()->getText();
                }
            }
        }

        if (empty($messageError)) {

            $moviment = (new Moviment());
            if (!empty($data['id'])) {
                $moviment = $moviment->findById($data['id']);
            }
            $moviment->bootstrap(
                $data['date_moviment'],
                $data['id_store'],
                $data['id_hour'],
                (!empty($data['id_list']) ? $data['id_list'] : null),
                money_fmt_app($data['beat_value']),
                money_fmt_app($data['paying_now']),
                money_fmt_app($data['expend']),
                money_fmt_app($data['last_value']),
                money_fmt_app($data['get_value']),
                money_fmt_app($data['new_value']),
                (!empty($data['prize']) ? money_fmt_app($data['prize']) : null),
                (!empty($data['beat_prize']) ? money_fmt_app($data['beat_prize']) : null),
                (!empty($data['prize_store']) ? money_fmt_app($data['prize_store']) : null),
                (!empty($data['prize_office']) ? money_fmt_app($data['prize_office']) : null)
            );
            if ($moviment->save()) {
                $json['message'] = $this->message->success("Tudo certo {$user->first_name}, o movimento atualizado com sucesso!")->render();
                $this->message->success("Tudo certo {$user->first_name}, o movimento atualizado com sucesso!")->flash();
                $json['reload'] = true;
                $json['scroll'] = 2;
            } else {
                $json['message'] = $moviment->message()->render();
            }
        } else {
            $json['message'] = $this->message->error($messageError);
        }
        echo json_encode($json);
        return true;
    }

    public function requiredMoviment(?array $data): ?string
    {
        $fields = [];
        foreach ($this->required as $field) {
            if (empty($data[$field]) && !($data[$field] === 0 || $data[$field] === '0')) {
                $fields[] = $field;
            }
        }
        if (!empty($fields)) {
            foreach ($fields as $value) {
                switch ($value) {
                    case 'id_hour':
                        $fildsArray[] = 'Horário';
                        break;
                    case 'id_store':
                        $fildsArray[] = 'Nome da Loja';
                        break;
                    case 'date_moviment':
                        $fildsArray[] = 'Data de movimento';
                        break;
                }
            }
            $message = 'Os seguintes campos são necessários: ' . implode(', ', $fildsArray) . ".";
            return $this->message->warning($message)->render();
        } else {
            return null;
        }
    }

    public function isRepeated(?string $dateMoviment, ?string $hour, ?string $store): bool
    {
        if (!empty($this->findByDateMoviment($dateMoviment, $hour, $store))) {
            return false;
        }
        return true;
    }

    protected function findByDateMoviment(?string $dateMoviment, ?string $id_hour, ?string $id_store): ?Moviment
    {
        $dateMoviment = date_fmt_app($dateMoviment);
        (new Moviment());
        return $this->find("DATE(date_moviment) = '{$dateMoviment}' AND id_store = {$id_store} AND id_hour = {$id_hour}",
            null, 'id')->fetch();
    }

    public function isEmpty(array &$data): void
    {
        $dataEmpty = [
            'paying_now',
            'expend',
            'get_value',
            'beat_value',
            'new_value',
            'prize',
            'beat_prize',
            'prize_store',
            'prize_office',
        ];

        if (empty($data[$dataEmpty[0]])) {
            $data[$dataEmpty[0]] = 0;
        }

        if (empty($data[$dataEmpty[1]])) {
            $data[$dataEmpty[1]] = 0;
        }

        if (empty($data[$dataEmpty[0]]) && empty($data[$dataEmpty[1]])) {
            $data[$dataEmpty[2]] = 0;
            $data[$dataEmpty[3]] = (!empty($data['id_list']) ? -abs($this->lists($data['id_list'])->net_value) : ($data['id_list'] = 0));
            if ($this->store($data['id_store'])->valor_saldo) {
                $data[$dataEmpty[4]] = ((float)($this->store($data['id_store'])->valor_saldo) + $data[$dataEmpty[3]]);
            }
        }
        if (empty($data[$dataEmpty[5]])) {
            $data[$dataEmpty[5]] = 0;
            $data[$dataEmpty[6]] = 0;
            $data[$dataEmpty[7]] = 0;
            $data[$dataEmpty[8]] = 0;
        }
    }

    public function filter(array $data): array
    {
        if (!empty($data['page'])) {
            array_pop($data);
        }
        // Se os filtros não foram submitados o padrão é adicionado a classe
        if (!empty($data)) {
            //formatando se existir
            if (!empty($data['search_date'])) {
                $date = str_replace('/', '-', $data['search_date']);
                $data['search_date'] = "DATE('" . date_fmt_app($date) . "')";
            }
            $filters = $data;
        } else {
            $filters = ['search_store' => '', 'search_hour' => '', 'search_date' => ''];
        }
        // passo o modelo e os filtros vazios ou não
        $filterClass = new FilterMoviment($this, $filters);

        $arrayFilterReturn = $filterClass->where([
            'search_store' => 'like',
            'search_hour' => 'like',
            'search_date' => 'equal'
        ],
            [
                'search_store' => 's.nome_loja',
                'search_hour' => 'h.description',
                'search_date' => "DATE(moviment.date_moviment)"
            ])
            ->find(['moviment.*', 'h.week_day', 'h.number_day', 'h.description as hour', 's.nome_loja']);

        $total = $filterClass->total([
            'prize' => 'total_prize',
            'expend' => 'total_expend',
            'get_value' => 'total_get_value'

        ], new Moviment());

        return array_merge($arrayFilterReturn, [$total]);
    }
}