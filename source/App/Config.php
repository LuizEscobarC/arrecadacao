<?php

namespace Source\App;

use Source\Models\Hour;
use Source\Models\Lists;
use Source\Models\Moviment;
use Source\Models\Store;
use Source\Support\HourManager;

class Config extends App
{
    public function __construct()
    {
        parent::__construct();

        // TEST $this->calcStore(['date_moviment' => '2022-04-29', 'id_hour' => '112']);
    }

    /**
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function closeHour(array $data): void
    {
        if (!empty($data)) {
            // post
            if (empty($data['id_hour'])) {
                $json = null;
                echo json_encode($json);
                return;
            }

            $hour = (new Hour())->findById($data['id_hour']);
            if (empty($hour)) {
                $json = null;
                echo json_encode($json);
                return;
            }
            // faz um toggle e atualiza o status
            $hour->status = ($hour->status == 1 ? 0 : 1);

            if ($hour->save()) {
                echo json_encode($json['response'] = true);
                return;
            } else {
                $json = null;
                echo json_encode($json);
                return;
            }
        }
        // META SEO
        $head = $this->seo->make("Configurações - ", url());

        // no banco deve haver o id 1 exclusivamente para o currentHour
        echo $this->view->render("configs/close-hour", [
            'view' => $this->view,
            "head" => $head,
            "hours" => HourManager::getHoursByDate((new \DateTime('now', (new \DateTimeZone('America/Sao_Paulo'))))
                ->format('Y-m-d')),
            'currentHour' => ((new \Source\Models\currentHour())->findById(1))->hour()
        ]);
    }

    public function calcStore(array $data): void
    {
        if (!empty($data['id_hour']) && !empty($data['date_moviment'])) {
            $date = date_fmt($data['date_moviment'], 'Y-m-d');
            $idHour = $data['id_hour'];

            // PEGA O ID DAS LOJAS QUE ENVIARAM LISTAS MAS NÃO TEM MOVIMENTO
            $storesQuery = (new Store());
            $storesQuery->find(null, null,
                'loja.id, loja.valor_saldo, l.net_value, l.id as id_list')
                ->join('lists l', 'loja.id', 'l.id_store', 'LEFT', ' = ',
                    " WHERE DATE(l.date_moviment) = DATE('{$date}') AND l.id_hour = {$idHour} AND loja.id 
                        NOT IN  
                            (
                                SELECT m.id_store
                                    FROM moviment m 
                                        WHERE DATE(m.date_moviment) = DATE('{$date}') AND m.id_hour = {$idHour}
                            )");
            
            $stores = $storesQuery->fetch(true);
            // PEGAR A LISTA DE CADA LOJA, SE HOUVER, SE NÃO REALIZAR CALCULO COM 0
            if (!empty($stores)) {
                foreach ($stores as $store) {
                    // FAZER O LANÇAMENTO DE MOVIMENTO DE CADA LOJA
                    // SALDO HORÁRIO
                    $beatValue = (0 - $store->net_value);
                    $newValue = ($store->valor_saldo + $beatValue);

                    // SAVE MOVIMENTS
                    $movimentSave = (new Moviment());

                    $movimentSave->date_moviment = $date;
                    $movimentSave->id_hour = $idHour;
                    $movimentSave->id_store = $store->id;
                    $movimentSave->last_value = $store->valor_saldo;
                    $movimentSave->id_list = $store->id_list;
                    $movimentSave->paying_now = 0;
                    $movimentSave->expend = 0;
                    $movimentSave->get_value = 0;
                    $movimentSave->beat_value = $beatValue;
                    $movimentSave->new_value = $newValue;
                    $movimentSave->prize = 0;
                    $movimentSave->beat_prize = 0;
                    $movimentSave->prize_office = 0;
                    $movimentSave->prize_store = 0;

                    if (!$movimentSave->save()) {
                        $message[] = $movimentSave->message()->render();
                    }
                }
            }
        } else {
            // TODAS AS LOJAS FORAM ABATIDAS
            $json['message'] = $this->message->warning("Todas as lojas do horário já foram abatidas na data {$date} e no horário " . (new Hour())->findById($idHour)->description . ".")->render();
            echo json_encode($json);
            return;
        }

        if (!empty($message)) {
            $json['message'] = implode(', ', $message);
            echo json_encode($json);
            return;
        } else {
            // TODAS AS LOJAS ABATIDAS COM SUCESSO
            $json['message'] = $this->message->success("Todas as lojas inadimplentes foram abatidas com sucesso na data {$date} e no horário " . (new Hour())->findById($idHour)->description . ".")->render();
            echo json_encode($json);
            return;
        }
    }
}