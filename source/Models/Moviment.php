<?php

namespace Source\Models;

use Source\Core\Controller;
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

    public function findByIdList(?string $id, string $columns = "*"): ?Model
    {
        $find = $this->find("id_list = :id", "id={$id}", $columns);
        return $find->fetch();
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

    public static function calculateMoviment(array $data)
    {
        $data = (object)$data;
        $data->id_list = (!empty($data->id_list) ? $data->id_list : null);
        $data->cents = (!empty($data->cents) ? money_fmt_app($data->cents) : null);
        $prize = (!empty($data->prize) ? money_fmt_app($data->prize) : 0);
        $data->last_value = money_fmt_app($data->last_value);
        $data->prize = $prize;
        $data->beat_prize = $prize;
        $data->paying_now = money_fmt_app($data->paying_now);
        $data->expend = money_fmt_app($data->expend);
        $getValue = $data->paying_now + $data->expend;
        $data->get_value = $getValue;
        $beatValue = money_fmt_app($data->last_value) + ($getValue - money_fmt_app($data->net_value));
        $data->beat_value = $beatValue;

        if ($beatValue >= 0 && $prize == 0) {
            $data->prize_store = 0;
            $data->prize_office = 0;
            $data->new_value = $data->beat_value;
        }

        if ($beatValue < 0 && $prize != 0 ) {
            $data->prize_store = 0;
            $data->prize_office = $data->prize;
            $data->new_value = $data->beat_value;
        }

        if ($beatValue >= 0 && $prize != 0 ) {
            $data->prize_store = 0;
            $data->prize_office = $data->prize;
            $data->new_value = $data->beat_value;
        }


        // SE O SALDO DO HORÁRIO DA LOJA FOR NEGATIVO
        if ($beatValue < 0) {
          if($data->shouldBeatPrizeStore) {
              $beatValueInverted = ($beatValue * (-1));
              if ($prize < $beatValueInverted) {
                  $data->prize_store = $prize;
                  $data->prize_office = 0;
                  $data->new_value = $prize - $beatValueInverted;
              }

              if ($prize >= $beatValueInverted) {
                  $data->prize_store = $beatValueInverted;
                  $data->prize_office = ($prize - $beatValueInverted);
                  $data->new_value = 0;
              }

              // VERIFICA SE TEM CENTAVOS
              if (preg_match("/\.([0-9]{1,}$)/", $data->prize_office, $matchs)) {
                  $data->cents = '0.' . $matchs[1];
                  $data->cents = (float)$data->cents;
              }

          }

          // SE NÃO FOR ABATER NO SALDO DA LOJA
          if (!$data->shouldBeatPrizeStore) {
              $data->prize_store = 0;
              $data->prize_office = $prize;
              $data->new_value = ($data->new_value - $beatValue);
          }

          if ($data->cents) {
              $data->new_value += $data->cents;
          }
        }
        $data->calc = true;

        // save temp
        $moviment = (new self);
        $moviment = $moviment->saveMoviment($moviment, $data);
        if (!$moviment->save()) {
            return $moviment->message()->render();
        }
        $data->idMovimentTemporary = $moviment->id;

        // DEVE RETORNAR OS DADOS CALCULADOS CORRETAMENTE
        return $data;
    }

    public function attach(array $data, User $user, int $movimentId): bool
    {
        // CURRENT HOUR SETTINGS IN DB
        Moviment::saveCurrentHour($data['id_hour']);

        // Se existir um movimento com a mesma data, horario e loja
        // CREATE OR UPDATE

        if (!empty($data['id_store'])) {
            $store = (new Store());
            $store = $store->findById($data['id_store']);
        }

        // BEGIN STORE
        if (!empty($data['new_value'])) {
            $store->valor_saldo = money_fmt_app($data['new_value']);
        }
        // ATUALIZA O SALDO DA LOJA DE UM MOVIMENTOU OU CRIA
        if (!$store->save()) {
            $json['message'] = $store->message()->getText();
            echo json_encode($json);
            return false;
        }
        // END STORE

        // BEGIN CASH FLOWS
        // retorna null se cadastrou tudo
        if (!empty($message = $this->cashFlowMoviment($data, $store, $movimentId))) {
            $json['message'] = $message;
            echo json_encode($json);
            return false;
        }
        // END CASH FLOWS
        $json['message'] = $this->message->success('Acerto de Loja cadastrado com sucesso!')->render();
        $json['reload'] = true;
        echo json_encode($json);
        return true;
    }

    public function editAndIncrementMoviment($data)
    {
        // CREATE OR UPDATE
        $moviment = Moviment::repeatedVerify($data);
        // CASO SEJA EDIÇÃO NÃO APLICA AS REGRAS DE INCLEMENTO DE DADOS
        if (!empty($data['edit'])) {
            $moviment->date_moviment = $data['date_moviment'];
            $moviment->id_store = $data['id_store'];
            $moviment->id_hour = $data['id_hour'];
            $moviment->id_list = (!empty($data['id_list']) ? $data['id_list'] : null);
            $moviment->beat_value = money_fmt_app($data['beat_value']);
            $moviment->paying_now = money_fmt_app($data['paying_now']);
            $moviment->expend = money_fmt_app($data['expend']);
            $moviment->last_value = money_fmt_app($data['last_value']);
            $moviment->get_value = money_fmt_app($data['get_value']);
            $moviment->new_value = money_fmt_app($data['new_value']);
            $moviment->prize = money_fmt_app($data['prize']);
            $moviment->beat_prize = money_fmt_app($data['beat_prize']);
            $moviment->prize_store = money_fmt_app($data['prize_store']);
            $moviment->prize_office = money_fmt_app($data['prize_office']);
        }

        // INCREMENTA
        if (!empty($moviment->id) && empty($data['edit'])) {
            // SE EXISTIR ID ATUALIZA, SE NÃO SALVA O MESMO VALOR
            $moviment->date_moviment = $data['date_moviment'] ?? $moviment->date_moviment;
            $moviment->id_store = $data['id_store'] ?? $moviment->id_store;
            $moviment->id_hour = $data['id_hour'] ?? $moviment->id_hour;
            $moviment->id_list = !empty($data['id_list']) ? $data['id_list'] : $moviment->id_list;
            $moviment->beat_value = money_fmt_app($data['beat_value']) ?? $moviment->beat_value;
            $moviment->paying_now =  is_not_zero($data['paying_now']) ? money_fmt_app($data['paying_now']) : $moviment->paying_now;
            $moviment->expend = is_not_zero($data['expend']) ? money_fmt_app($data['expend']) : $moviment->expend;
            $moviment->last_value =  $moviment->last_value ?? money_fmt_app($data['last_value']);
            $moviment->get_value =  is_not_zero($data['paying_now']) ? money_fmt_app($data['get_value']) : $moviment->get_value;
            $moviment->new_value =  money_fmt_app($data['new_value']) ?? $moviment->new_value;
            $moviment->prize =  (money_fmt_app($data['prize']) + $moviment->prize);
            $moviment->beat_prize =  (money_fmt_app($data['beat_prize']) + $moviment->beat_prize);
            $moviment->prize_store =  (money_fmt_app($data['prize_store']) + $moviment->prize_store);
            $moviment->prize_office = (money_fmt_app($data['prize_office']) + $moviment->prize_office);
        }
    }

    public function saveMoviment(Moviment $moviment, $data)
    {
        $data = (array)$data;
        if (empty($moviment->id)) {
            $moviment->date_moviment = $data['date_moviment'];
            $moviment->id_store = $data['id_store'];
            $moviment->id_hour = $data['id_hour'];
            $moviment->id_list = !empty($data['id_list']) ? $data['id_list'] : null;
            $moviment->beat_value = money_fmt_app($data['beat_value']);
            $moviment->paying_now = money_fmt_app($data['paying_now']);
            $moviment->expend = money_fmt_app($data['expend']);
            $moviment->last_value = money_fmt_app($data['last_value']);
            $moviment->get_value = money_fmt_app($data['get_value']);
            $moviment->new_value = money_fmt_app($data['new_value']);
            $moviment->prize = money_fmt_app($data['prize']);
            $moviment->beat_prize = money_fmt_app($data['beat_prize']);
            $moviment->prize_store = money_fmt_app($data['prize_store']);
            $moviment->prize_office = money_fmt_app($data['prize_office']);
        }

        return $moviment;
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

    public function isRepeated(?string $dateMoviment, ?string $hour, ?string $store): ?Moviment
    {
        return $this->findByDateMoviment($dateMoviment, $hour, $store);
    }

    protected function findByDateMoviment(?string $dateMoviment, ?string $id_hour, ?string $id_store): ?Moviment
    {
        $dateMoviment = date_fmt_app($dateMoviment);
        return $this->find("DATE(date_moviment) = DATE('{$dateMoviment}') AND id_store = {$id_store} AND id_hour = {$id_hour}")->fetch();
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
                $data[$dataEmpty[4]] = (money_fmt_app($this->store($data['id_store'])->valor_saldo) + money_fmt_app($data[$dataEmpty[3]]));
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
        if (!empty($date)) {
            $arrayFilterReturn[1]['search_date'] = date_fmt_app($date);
        }
        return array_merge($arrayFilterReturn, [$total]);
    }

    private function cashFlowMoviment(array $data, Store $store, int $idMoviment): ?string
    {
        // LISTA DO HORÁRIO
        if (!empty($data['id_list'])) {
            $list = (new Lists())->findById($data['id_list']);
        }

        $cashSearch = new CashFlow();

        if (is_not_zero((float)money_fmt_app($data['prize_office']))) {
            $cash = ($cashSearch->findByDateMoviment($idMoviment, 2,
                    17) ?? new CashFlow());

            // PREMIO ESCRITÓRIO DESPESA
            $cash->date_moviment = $data["date_moviment"];
            $cash->id_store = $data["id_store"];
            $cash->id_hour = $data["id_hour"];
            $cash->description = 'Saída de Premio do Escritório';
            // SE FOR ATUALIZAÇÃO DE MOVIMENTO, INCREMENTA
            if ($cash->id) {
                if (empty($data['edit'])) {
                    $cash->value += money_fmt_app($data['prize_office']);
                } else {
                    $cash->value = money_fmt_app($data['prize_office']);
                }
            } else {
                $cash->value = money_fmt_app($data['prize_office']);
            }
            $cash->type = 2;
            $cash->id_cost = 17;
            $cash->id_moviment = $idMoviment;

            if (!$cash->save()) {
                return $cash->message()->getText();
            }
        }

        if (is_not_zero((float)money_fmt_app($data['prize_store']))) {
            $cash = ($cashSearch->findByDateMoviment($idMoviment, 2,
                    4) ?? new CashFlow());

            // PREMIO LOJA PAGOU POREM É DESPESA
            $cash->date_moviment = $data["date_moviment"];
            $cash->id_store = $data["id_store"];
            $cash->id_hour = $data["id_hour"];
            $cash->description = 'Abate de Premio da loja ' . $store->nome_loja;
            // SE FOR ATUALIZAÇÃO DE MOVIMENTO, INCREMENTA
            if ($cash->id) {
                if (empty($data['edit'])) {
                    $cash->value += money_fmt_app($data['prize_store']);
                } else {
                    $cash->value = money_fmt_app($data['prize_store']);
                }
            } else {
                $cash->value = money_fmt_app($data['prize_store']);
            }
            $cash->type = 2;
            $cash->id_cost = 4;
            $cash->id_moviment = $idMoviment;

            if (!$cash->save()) {
                return $cash->message()->getText();
            }
        }

        if (is_not_zero((float)money_fmt_app($data['get_value']))) {
            $cash = ($cashSearch->findByDateMoviment($idMoviment, 1,
                    16) ?? new CashFlow());

            // VALOR RECOLHIDO DA LOJA
            $cash->date_moviment = $data["date_moviment"];
            $cash->id_store = $data["id_store"];
            $cash->id_hour = $data["id_hour"];
            $cash->description = ($list->description ?? '') . ' Entrada de ' . ($store->nome_loja ?? 'loja');
            // SE FOR ATUALIZAÇÃO DE MOVIMENTO, INCREMENTA
            if ($cash->id) {
                if (empty($data['edit'])) {
                    $cash->value += money_fmt_app($data["get_value"]);
                } else {
                    $cash->value = money_fmt_app($data["get_value"]);
                }
            } else {
                $cash->value = money_fmt_app($data["get_value"]);
            }
            $cash->type = 1;
            $cash->id_cost = 16;
            $cash->id_moviment = $idMoviment;

            if (!$cash->save()) {
                return $cash->message()->getText();
            }

            // DESPESAS DA LOJA
            if (is_not_zero((float)money_fmt_app($data['expend']))) {
                $cash = ($cashSearch->findByDateMoviment($idMoviment, 2,
                        2) ?? new CashFlow());

                $cash->date_moviment = $data["date_moviment"];
                $cash->id_store = $data["id_store"];
                $cash->id_hour = $data["id_hour"];
                $cash->description = ' A ' . ($store->nome_loja ?? 'loja') . ' teve uma despesa';
                // SE FOR ATUALIZAÇÃO DE MOVIMENTO, INCREMENTA
                if ($cash->id) {
                    if (empty($data['edit'])) {
                        $cash->value += money_fmt_app($data["expend"]);
                    } else {
                        $cash->value = money_fmt_app($data["expend"]);
                    }
                } else {
                    $cash->value = money_fmt_app($data["expend"]);
                }
                $cash->type = 2;
                $cash->id_cost = 2;
                $cash->id_moviment = $idMoviment;

                if (!$cash->save()) {
                    return $cash->message()->getText();
                }
            }
        }
        // DEU TUDO CERTO
        return null;
    }

    public function getMoviment(array $data): ?Model
    {
        return $this->find("DATE(date_moviment) = DATE('{$data['date_moviment']}') AND id_hour = {$data['id_hour']} AND id_store = {$data['id_store']}")->fetch();
    }

    public static function saveCurrentHour($id)
    {
        $currentHour = (new currentHour())->findById(1);
        $currentHour->current_hour = $id;
        $currentHour->save();
    }

    public function filterQuery(array $filter, string $fixed, ?int $limit = null)
    {

        // QUERYS
        $storeQuery = (!empty($filter['search_store']) && $filter['search_store'] != 'all' ? "AND id_store = {$filter['store']}" : null);
        $hourQuery = (!empty($filter['search_hour']) && $filter['search_hour'] != 'all' ? "AND id_hour = {$filter['search_hour']}" : null);
        $dateQuery = (!empty($filter['date_moviment']) && $filter['date_moviment'] != 'all' ? "AND DATE(date_moviment) = DATE('{$filter['date_moviment']}')" : null);

        // CASO PROCURE PELO NOME DO HORÁRIO É PRECISO FAZER UM JOIN
        if ($hourQuery) {
            $cashFlows = $this->find()
                ->join('hour h', 'moviment.id_hour', 'h.id');
            $cashFlows->putQuery("{$fixed} {$storeQuery} {$hourQuery} {$dateQuery}");
        } else {
            $cashFlows = $this->find("{$fixed} {$storeQuery} {$hourQuery} {$dateQuery}");
        }
        if ($limit) {
            $cashFlows->limit($limit);
        }
        return $cashFlows->fetch(true);
    }

    public static function repeatedVerify(array $data)
    {
         return (new self)->isRepeated($data['date_moviment'], $data['id_hour'],
            $data['id_store']);
    }
}