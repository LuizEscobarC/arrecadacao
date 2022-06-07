<?php

namespace Source\App\Store;

use Source\App\App;
use Source\Core\View;
use Source\Models\Store;
use Source\Support\Pager;
use function money_fmt_app;
use function more_than_on_negative;
use function url;

class StoreController extends App
{

    /**
     * IT PRESENTES THE REGISTERS OF STORE TABLE
     * @param array|null $data
     * @return void
     */
    public function stores(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Lojas - ", url('/app/lojas'));

        $search = filter_var((!empty($data['search']) ? $data['search'] : null), FILTER_SANITIZE_STRIPPED);
        $situation = filter_var((!empty($data['store_situation']) ? $data['store_situation'] : null), FILTER_SANITIZE_STRIPPED);

        $storeInstance = new Store();
        $implode[] = ( !empty($search) ? "nome_loja LIKE '%{$search}%'" : null);
        $implode[] = ( !empty($situation) && $situation == 1 ? "valor_saldo >= 0" : ($situation == 2 ? "valor_saldo < 0" : null));

        foreach($implode as $key => $value) {
            if (empty($value)) {
                unset($implode[$key]);
            }
        }

        if (!empty($implode)) {
            $query = implode(' AND ', $implode);
        };


        $stores = $storeInstance->find(($query ?? null))->order('code');


        $values = new \stdClass();
        $values->total = (new Store())->find('', null, 'sum(valor_saldo) as total')->fetch()->total;
        $values->totalNegative = (new Store())->find('valor_saldo < 0', null, 'sum(valor_saldo) as total')->fetch()->total;
        $values->totalPositive = (new Store())->find('valor_saldo >= 0', null, 'sum(valor_saldo) as total')->fetch()->total;
        if(empty($query)) {
            $page = (!empty($data['page']) ? $data['page'] : 1);
            $pager = (new Pager(url('/app/lojas/')));
            $pager->pager($stores->count(), 20, $page);
            $paginator = $pager->render();
            $limit = $pager->limit();
            $offset = $pager->offset();
        } else {
            $paginator = null;
        }

        $stores = (!empty($paginator) ?  $stores->limit($limit)->offset($offset)->fetch(true) : $stores->fetch(true));

        echo $this->view->render("stores", [
            "head" => $head,
            'stores' => $stores,
            'paginator' => $paginator,
            'search' => ($search ?? null),
            'values' => $values
        ]);
    }

    /**
     * APP STORE EDIT VIEW | IT PRESENTS THE CURRENT STORE EDIT SCREEN
     * @param array $data
     */
    public function store(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Lojas - ", url('/app/loja'));

        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);

        if (empty($id) || empty((new Store())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Essa Loja',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/lojas')
                ]
            ]);
            return;
        }

        $store = (new Store())->findById($id);

        echo $this->view->render("store", [
            "head" => $head,
            'store' => $store
        ]);
    }

    /**
     * @return void
     */
    public function createStore(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Loja - ", url());

        echo $this->view->render("creates/store", [
            "head" => $head
        ]);
    }

    /**
     *  IT UPDATES OR CREATES CURRENT STORE OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
     * @param array|null $data
     */
    public function saveStore(?array $data)
    {
        if (!empty($data)) {

            $store = (new Store());

            if (!empty($store->findByName($data['nome_loja'])) && empty($data['id'])) {
                $json['message'] = $this->message->warning('Essa loja já está cadastrada!')->render();
                echo json_encode($json);
                return;
            }

            if (!empty($data['id'])) {
                $store = $store->findById($data['id']);
            }

            // VERIFICAR SE VEIO UM NUMERO NEGATIVO COM MAIS DE 1 NEGATIVO NA STRING
            if (!more_than_on_negative([
                $data["valor_saldo"],
                $data["comissao"],
                (!empty($data["valor_aluguel"]) ? $data["valor_aluguel"] : ''),
                (!empty($data["aluguel_dia"]) ? $data["aluguel_dia"] : ''),
                (!empty($data["valor_gratificacao"]) ? $data["valor_gratificacao"] : ''),
                (!empty($data["gratificacao_dia"]) ? $data["gratificacao_dia"] : '')
            ])) {
                $json['message'] = $this->message->error('Verifique os campos de entrada de dinheiro, há algum problema.')->render();
                $json['scroll'] = 100;
                echo json_encode($json);
                return;
            }

            $store->bootstrap(
                $data["nome_loja"],
                money_fmt_app($data["valor_saldo"]),
                money_fmt_app($data["comissao"]),
                (!empty($data["valor_aluguel"]) ? money_fmt_app($data["valor_aluguel"]) : null),
                (!empty($data["aluguel_dia"]) ? money_fmt_app($data["aluguel_dia"]) : null),
                (!empty($data["valor_gratificacao"]) ? money_fmt_app($data["valor_gratificacao"]) : null),
                (!empty($data["gratificacao_dia"]) ? money_fmt_app($data["gratificacao_dia"]) : null),
                $data['code']
            );

            if (!$store->save()) {
                $json['message'] = $store->message()->render();
            } else {
                $json['message'] = $this->message->success("Loja atualizada com sucesso!")->render();
                $this->message->success("Loja atualizada com sucesso!")->flash();
                $json['reload'] = true;
                $json['scroll'] = 100;
            }
        }

        echo json_encode($json);
    }

    /**
     * IT REMOVES CURRENT STORE WITH ONE CLICK
     * @param array $data
     * @return void
     */
    public function removeStore(array $data): void
    {
        $hour = (new Store())->findById($data['id']);
        if ($hour) {
            $hour->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, loja removida com sucesso!")->flash();
        $json['message'] = $this->message->success("Tudo pronto {$this->user->first_name}, loja removida com sucesso!")->render();
        $json['scroll'] = 10;
        $json['redirect'] = url('app/lojas');
        echo json_encode($json);
        return;
    }

}