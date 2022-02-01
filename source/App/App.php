<?php

namespace Source\App;

use Composer\Package\Loader\ValidatingArrayLoader;
use Source\Core\Connect;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\CashFlow;
use Source\Models\Center;
use Source\Models\Hour;
use Source\Models\Lists;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\Store;
use Source\Models\User;
use Source\Support\Message;
use Source\Support\Pager;

/**
 * Class App
 * @package Source\App
 */
class App extends Controller
{
    /** @var User */
    private $user;

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!$this->user = Auth::user()) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }

    }

    /**
     * APP HOME
     */
    public function home(): void
    {
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("home", [
            "head" => $head
        ]);
    }

    /**
     * APP LIST USERS
     */
    public function users(array $data): void
    {
        $head = $this->seo->render(
            "Meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
        $user = new User();
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/usuarios/'));
        $pager->pager($user->find()->count(), 20, $page);

        echo $this->view->render("users", [
            "head" => $head,
            'users' => $user
                ->find()
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->order('created_at')
                ->fetch(true),
            'paginator' => $pager->render()
        ]);
    }

    /**
     * APP PROFILE (Perfil)
     * @param array $data
     */
    public function profile(array $data): void
    {
        $head = $this->seo->render(
            "Meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);

        if (empty($id) || !$id) {
            $this->message->error('Erro ao tentar acessar o usuário, por favor entre em contato com o desenvolvedor.')
                ->flash();
            redirect('/app/usuarios');
        }

        echo $this->view->render("profile", [
            "head" => $head,
            'user' => (new User())->findById($data['id'])
        ]);
    }


    /**
     * APP REGISTER USER
     * @param array|null $data
     */
    public function register(?array $data): void
    {
        if (!empty($data)) {
            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Informe seus dados para editar o usuário.")->render();
                echo json_encode($json);
                return;
            }

            if (!empty($data['password_re'])) {
                if ($data['password'] != $data['password_re']) {
                    $json['message'] = $this->message->warning("Informe senhas iguais.")->render();
                    echo json_encode($json);
                    return;
                }
            }

            $auth = new Auth();
            $user = new User();
            // Se for atualização de usuário ele vai buscar o usuário
            if (!empty($data['id'])) {
                $user = $user->findById($data['id']);
            }
            $user->bootstrap(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["password"]
            );

            if ($auth->register($user)) {
                $json['message'] = $auth->message()->success("Usuário editado com sucesso!")->render();
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Criar Conta - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/cadastrar"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function removeUser(array $data): void
    {
        $hour = (new User())->findById($data['id']);
        if ($hour) {
            $hour->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, usuário removido com sucesso!")->flash();
        $json['redirect'] = url('/app');
        echo json_encode($json);
    }

    /**
     * SITE OPT-IN SUCCESS
     * @param array $data
     */
    public function success(array $data): void
    {
        $email = $data['email'];
        $user = (new User())->findByEmail($email);

        if ($user && $user->status != "confirmed") {
            $user->status = "confirmed";
            $user->save();
        }

        $head = $this->seo->render(
            "Bem-vindo(a) ao " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/obrigado"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object)[
                "title" => "Tudo pronto :)",
                "desc" => "Cadastrado com sucesso",
                "image" => theme("/assets/images/optin-success.jpg"),
                "link" => url("/app/users"),
                "linkTitle" => "Cadastrado com sucesso"
            ]
        ]);
    }

    /**
     * APP LOGOUT
     */
    public function logout(): void
    {
        (new Message())->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }

    /**
     * LISTAGEM DE LOJAS
     */
    public function stores(?array $data): void
    {
        $head = $this->seo->render(
            "Lojas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/lojas'),
            theme("/assets/images/share.jpg"),
            false
        );

        $search = filter_var((!empty($data['search']) ? $data['search'] : null), FILTER_SANITIZE_STRIPPED);


        if ($search) {
            $stores = (new Store())->find("MATCH(nome_loja, code) AGAINST(:s)", "s={$search}");
        } else {
            $stores = (new Store())->find()->order('id');
            $search = null;
        }
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/lojas/'));
        $pager->pager($stores->count(), 5, $page);
        echo $this->view->render("stores", [
            "head" => $head,
            'stores' => $stores
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'paginator' => $pager->render(),
            'search' => ($search ?? null)
        ]);
    }

    /**
     * APP STORE EDIT VIEW
     * @param array $data
     */
    public function store(array $data): void
    {
        if (empty($data['id'])) {
            $json['message'] = $this->message->error('Loja sem identificação, por favor contate o desenvolvedor!')->render();
            echo json_encode($json);
            return;
        }

        if (!$id = filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $json['message'] = $this->message->error('Escolha uma loja válida!')->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Loja - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/loja'),
            theme("/assets/images/share.jpg"),
            false
        );
        $store = (new Store())->findById($id);

        echo $this->view->render("store", [
            "head" => $head,
            'store' => $store
        ]);
    }

    /**
     * STORE POST SAVE AND EDIT DATA
     * @param array|null $data
     */
    public function storeSave(?array $data)
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


            $store->bootstrap(
                $data["nome_loja"],
                $data["valor_saldo"],
                $data["comissao"],
                $data["valor_aluguel"],
                $data["aluguel_dia"],
                $data["valor_gratificacao"],
                $data["gratificacao_dia"],
                $data['code']
            );

            if (!$store->save()) {
                $json['message'] = $store->message()->render();
            } else {
                $json['message'] = $this->message->success("Loja atualizada com sucesso!")->render();
                $json['redirect'] = url("/app/lojas");
            }
        }

        echo json_encode($json);
    }

    /**
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
        $json['redirect'] = url('/app');
        echo json_encode($json);
    }

    /**
     * @return void
     */
    public function costCenters(?array $data): void
    {
        $head = $this->seo->render(
            "Centro de Custos - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/centro-de-custos'),
            theme("/assets/images/share.jpg"),
            false
        );

        $searchDay = filter_var((!empty($data['day']) ? $data['day'] : null), FILTER_VALIDATE_INT);

        if ($searchDay) {
            $center = (new Center())->find("DAY(created_at) = :s", "s={$searchDay}");
        } else {
            $center = (new Center())->find();
            $searchDay = null;
        }

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/centro-de-custos/'));
        $pager->pager($center->count(), 20, $page);

        echo $this->view->render('cost-centers', [
            'head' => $head,
            'costCenters' => $center
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'paginator' => $pager->render(),
            'search' => ($searchDay ?? null)
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function costCenter(array $data): void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

        $head = $this->seo->render(
            "Centro de Custo - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/centro-de-custo'),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render('cost-center', [
            'head' => $head,
            'costCenter' => (new Center())->findById($id)
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function saveCenter(array $data): void
    {

        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($data)) {
            //atualizar
            $center = (new Center());

            if (!empty($data['id'])) {
                $center = $center->findById($data['id']);
            }
            $center->bootstrap($data['description'], $data['emit']);

            if (!$center->save()) {
                $json['message'] = $center->message()->render();
            } else {
                $json['message'] = $this->message->success('Centro de custo atualizado com sucesso!')->render();
                $json['redirect'] = url("/app/centros-de-custo");
            }
        }

        echo json_encode($json);
    }

    /**
     * @param array $data
     * @return void
     */
    public function removeCenter(array $data): void
    {
        $center = (new Center())->findById($data['id']);
        if ($center) {
            $center->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, centro de custo removido com sucesso!")->flash();
        $json['redirect'] = url('/app');
        echo json_encode($json);
    }

    /**
     * @return void
     */
    public function hours(): void
    {
        $head = $this->seo->render(
            "Horários - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/horarios'),
            theme("/assets/images/share.jpg"),
            false
        );

        $hour = new Hour();
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/usuarios/'));
        $pager->pager($hour->find()->count(), 20, $page);

        echo $this->view->render('hours', [
            'head' => $head,
            'hours' => $hour->find()
                ->order('week_day')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'paginator' => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @return void
     */
    public function hour(?array $data): void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

        $head = $this->seo->render(
            "Horário - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/horario'),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render('hour', [
            'head' => $head,
            'hour' => (new Hour())->findById($id)
        ]);
    }

    /**
     * @param array|null $data
     * @return void
     */
    public function saveHour(?array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($data)) {
            //atualizar
            $hour = (new Hour());
            if (in_array('', $data)) {
                $json['message'] = $this->message->warning('Todos os campos são necessários!')->render();
                echo json_encode($json);
                return;
            }

            if (!empty($data['id'])) {
                $hour = $hour->findById($data['id']);
            }
            switch ($data['number_day']) {
                case 0:
                    $data['week_day'] = 'domingo';
                    break;
                case 1:
                    $data['week_day'] = 'segunda-feira';
                    break;
                case 2:
                    $data['week_day'] = 'terça-feira';
                    break;
                case 3:
                    $data['week_day'] = 'Quarta-feira';
                    break;
                case 4:
                    $data['week_day'] = 'Quinta-feira';
                    break;
                case 5:
                    $data['week_day'] = 'Sexta-feira';
                    break;
                case 6:
                    $data['week_day'] = 'Sábado';
                    break;
            }

            $hour->bootstrap((string)$data['number_day'], $data['week_day'], $data['description']);

            if (!$hour->save()) {
                $json['message'] = $hour->message()->render();
            } else {
                $json['message'] = $this->message->success('Horário de custo atualizado com sucesso!')->render();
                $json['redirect'] = url("/app/horarios");
            }
        }

        echo json_encode($json);
    }

    public function getHour(array $data): void
    {
        $getDayNumber = weekDay($data['date_moviment'], true);
        $dataDay = (new Hour())->findByNumberDay($getDayNumber);
        $i = 0;

        foreach ($dataDay as $item) {
            $callback[$i]['id'] = $item->id;
            $callback[$i]['description'] = $item->description;
            $i++;
        }
        echo json_encode($callback);
    }

    public function getWeekDay(?array $data): void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        $weekDay = (new Hour())->findById($id)->week_day;
        $callback['week_day'] = $weekDay;
        echo json_encode($callback);
    }

    /**
     * @param array $data
     * @return void
     */
    public function removeHour(array $data): void
    {
        $hour = (new Hour())->findById($data['id']);
        if ($hour) {
            $hour->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, horário removido com sucesso!")->flash();
        $json['redirect'] = url('/app');
        echo json_encode($json);
    }

    public function lists(?array $data): void
    {
        $head = $this->seo->render(
            "Listas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/listas'),
            theme("/assets/images/share.jpg"),
            false
        );

        list($list, $search) = (new Lists())->listFilters($data);

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/listas/'));
        $pager->pager($list->count(), 20, $page);


        echo $this->view->render('lists', [
            'head' => $head,
            'lists' => $list->order('lists.date_moviment, s.nome_loja')
                ->offset($pager->offset())
                ->limit($pager->limit())
                ->fetch(true),
            'allMoney' => (new Lists())->find(null, null, 'sum(total_value) as value')->fetch(),
            'paginator' => $pager->render(),
            'search' => ((object)$search ?? null)
        ]);
    }

    public function list(?array $data): void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

        $head = $this->seo->render(
            "Lista - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/lista'),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render('list', [
            'head' => $head,
            'list' => (new Lists())->findById($id)
        ]);
    }

    public function saveList(array $data): void
    {

        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($data)) {
            //atualizar
            $list = (new Lists());

            if (!empty($data['id'])) {
                $list = $list->findById($data['id']);
            }

            $list->bootstrap($data['id_hour'], $data['id_store'], $data['total_value'], $data['date_moviment']);

            /** @var Lists $list */
            if (!$list->save()) {
                $json['message'] = $list->message()->render();
            } else {
                $json['message'] = $this->message->success('Lista atualizado com sucesso!')->render();
                $json['redirect'] = url("/app/listas");
            }
        }

        echo json_encode($json);
    }

    public function removeList(array $data): void
    {
        $list = (new Lists())->findById($data['id']);
        if ($list) {
            $list->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, lista de custo removido com sucesso!")->flash();
        $json['redirect'] = url('/app');
        echo json_encode($json);
    }

    public function cashFlows(?array $data): void
    {
        $head = $this->seo->render(
            "Fluxo - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/fluxos-de-caixa'),
            theme("/assets/images/share.jpg"),
            false
        );

        //list($list, $search) = (new Lists())->listFilters($data);
        $cashFlows = (new CashFlow())->find(null, null,
            'cash_flow.*, h.week_day, h.number_day, h.description as hour, s.nome_loja, cc.description as cost')
            ->join('hour h', 'h.id', 'cash_flow.id_hour')
            ->join('loja s', 's.id', 'cash_flow.id_store')
            ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager('/arrecadacao/app/fluxos-de-caixa'));
        $pager->pager($cashFlows->count(), 20, $page);


        echo $this->view->render('cash-flows', [
            'head' => $head,
            'cashFlows' => $cashFlows->order('cash_flow.date_moviment, h.number_day, s.nome_loja')
                ->offset($pager->offset())
                ->limit($pager->limit())
                ->fetch(true),
            'allMoney' => (new $cashFlows())->find(null, null, 'sum(value) as value')->fetch(),
            'paginator' => $pager->render(),
            'search' => ((object)'$search' ?? null)
        ]);
    }

    public function cashFlow(array $data): void
    {
        $head = $this->seo->render(
            "Editar lançamento - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/fluxo-de-caixa'),
            theme("/assets/images/share.jpg"),
            false
        );
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

       echo $this->view->render('cash-flow', [
            'head' => $head,
            'cash' => (new CashFlow())->findById($id)
        ]);
    }

    public function saveCashFlow(?array $data): void
    {
        if (!empty($data)) {
            $cash = (new CashFlow());

            if (!empty($data['id'])) {
                $cash = $cash->findById($data['id']);
            }

            $cash->bootstrap(
                $data["date_moviment"],
                $data["id_store"],
                $data["id_hour"],
                $data["description"],
                $data["value"],
                $data["type"],
                $data["id_cost"]
            );

            if (!$cash->save()) {
                $json['message'] = $cash->message()->render();
            } else {
                $json['message'] = $this->message->success("Lançamento atualizado com sucesso!")->render();
                $json['redirect'] = url("/app/fluxos-de-caixa");
            }
        }

        echo json_encode($json);
    }

    public function removeCashFlow(array $data): void
    {
        $cashFlow = (new CashFlow())->findById($data['id']);
        if ($cashFlow) {
            $cashFlow->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, lançamento removido com sucesso!")->flash();
        $json['redirect'] = url('/app/fluxos-de-caixa');
        echo json_encode($json);
    }

}

