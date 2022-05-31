<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Core\View;
use Source\Models\Auth;
use Source\Models\CashFlow;
use Source\Models\Center;
use Source\Models\Hour;
use Source\Models\Lists;
use Source\Models\Moviment;
use Source\Models\SelfList;
use Source\Models\Store;
use Source\Models\User;
use Source\Support\HourManager;
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
        // RESETA O STATUS DE FECHAMENTO DE HORÁRIO NA TABELA HOUR
        (new Hour())->resetStatus();
        function validar(array $date)
        {
            return \DateTime::createFromFormat('d-m-Y', $date[0])->format('d/m/Y');
        }
    }

    /**
     * APP HOME
     */
    public function home(): void
    {
        // META SEO
        $head = $this->seo->make("Olá {$this->user->first_name}. - ", url());
        if (Auth::user()->level != 1) {
            echo $this->view->render("views/home-user", [
                'head' => $head,
                'error' => (object)[
                    'linkTitle' => 'Cadastrar Acerto de Lojas',
                    'link' => url('/app/cadastrar-movimentacao'),
                    'message' => 'Welcome',
                    'name' => $this->user->first_name
                ]
            ]);
            return;
        }
        //CHART
        $chartData = (new CashFlow())->chartData();
        //END CHART

        // BEGIN BALANCE RETORNA OS VALORES DE DESPESAS, RECEITAS, TOTAL DE FATURA DO MES, TOTAL DE DESPESAS E TOTAL DE DESPESAS
        $balance = (new CashFlow())->balance();
        // END BALANCE

        echo $this->view->render("home", [
            "head" => $head,
            "chart" => $chartData,
            //data, nome e valor
            "expenses" => $balance->expenses,
            "incomes" => $balance->incomes,
            "totalMonth" => $balance->totalBilling,
            'bothValues' => (object)[
                'total_incomes' => $balance->totalIncomes,
                'total_expenses' => $balance->totalExpenses
            ]
        ]);
    }

    /**
     * @return void
     */
    public function ajaxGrap()
    {

        //PARA ATUALIZAR EM TEMPO REAL O GRAFICO TEM QUE SER ENVIADOS ARRAYS COM A MESMA QUANTIDADE DE INDICES
        $chartData = (new CashFlow())->chartData();
        $categories = str_replace("'", "", explode(",", $chartData->date_moviment));
        $callback["chart"] = [
            "date_moviment" => $categories,
            "income" => array_map("abs", explode(",", $chartData->income)),
            "expense" => array_map("abs", explode(",", $chartData->expense))
        ];
        echo json_encode($callback);
    }

    /**
     * IT PRESENTES THE REGISTERS OF USERS TABLE
     * @param array $data
     * @return void
     */
    public function users(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Meu perfil - ", url());

        $user = new User();
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/usuarios/')));
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
     * APP PROFILE (Perfil) IT PRESENTS THE CURRENT USER EDIT SCREEN
     * @param array $data
     */
    public function profile(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Meu perfil - ", url());

        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);

        if (empty($id) || empty((new User())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Esse usuário',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/usuarios')
                ]
            ]);
            return;
        }
        echo $this->view->render("profile", [
            "head" => $head,
            'user' => (new User())->findById($data['id'])
        ]);
    }

    /**
     * @return void
     */
    public function createUser(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar usuário - ", url());

        echo $this->view->render("creates/user", [
            "head" => $head
        ]);
    }


    /**
     * APP REGISTER USER |  IT UPDATES OR CREATES CURRENT USER OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
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
            $user->level = ($data['level'] ?? 2);

            if ($auth->register($user)) {
                $json['message'] = $auth->message()->success("Tudo Pronto {$this->user->first_name}, o usuário foi atualizado com sucesso!")->render();
                $auth->message()->success("Tudo Pronto {$this->user->first_name}, o usuário foi atualizado com sucesso!")->flash();
                $json['reload'] = true;
                $json['scroll'] = 100;
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }
        // META SEO
        $head = $this->seo->make("Criar Conta - ", url("/cadastrar"));
        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    /**
     * IT REMOVES CURRENT USER OF TABLE WITH ONE CLICK
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
        $json['redirect'] = url('/app/usuarios');
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
        // META SEO
        $head = $this->seo->make("Bem-vindo(a) ao ", url("/obrigado"));
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


    /**
     * IT PRESENTES THE REGISTERS OF COST CENTER TABLE
     * @param array|null $data
     * @return void
     */
    public function costCenters(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Centro de Custos - ", url('/app/centro-de-custos'));

        $searchDay = filter_var((!empty($data['day']) ? $data['day'] : null), FILTER_VALIDATE_INT);

        if ($searchDay) {
            $center = (new Center())->find("DAY(created_at) = :s", "s={$searchDay}");
        } else {
            $center = (new Center())->find();
            $searchDay = null;
        }

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/centros-de-custo/')));
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
     * APP COST CENTER EDIT VIEW | IT PRESENTS THE CURRENT COST CENTER EDIT SCREEN
     * @param array $data
     * @return void
     */
    public function costCenter(array $data): void
    {
        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);
        // META SEO
        $head = $this->seo->make("Centro de Custo - ", url('/app/centro-de-custo'));

        if (empty($id) || empty((new Center())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Esse centro de custo',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/centro-de-custo')
                ]
            ]);
            return;
        }

        echo $this->view->render('cost-center', [
            'head' => $head,
            'costCenter' => (new Center())->findById($id)
        ]);
    }

    /**
     * @return void
     */
    public function createCost(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Centro de Custos - ", url());

        echo $this->view->render("creates/cost", [
            "head" => $head
        ]);
    }

    /**
     *  IT UPDATES OR CREATES CURRENT COST CENTER OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
     * @param array $data
     * @return void
     */
    public function saveCenter(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($data)) {
            if (empty($data['emit'])) {
                $json['message'] = $this->message->warning("Selecionar Sim ou Não para emitir recibos é obrigatório.")->render();
                echo json_encode($json);
                return;
            }
            // ATUALIZAR
            $center = (new Center());

            if (!empty($data['id'])) {
                $center = $center->findById($data['id']);
            }
            // CADASTRO
            $center->description = $data['description'];
            $center->emit = $data['emit'];

            if (!$center->save()) {
                $json['message'] = $center->message()->render();
            } else {
                $json['message'] = $this->message->success('Centro de custo atualizado com sucesso!')->render();
                $this->message->success('Centro de custo atualizado com sucesso!')->flash();
                $json['reload'] = true;
            }
        }

        echo json_encode($json);
    }

    /**
     * IT REMOVES CURRENT COST CENTER OF TABLE WITH ONE CLICK
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
        $json['redirect'] = url('/app/centros-de-custo');
        echo json_encode($json);
    }


    /**
     * IT PRESENTES THE REGISTERS OF HOUR TABLE
     * @return void
     */
    public function hours(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Horários - ", url('/app/horarios'));

        $hour = (new Hour())->find();
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/horarios/')));
        $pager->pager($hour->count(), 15, $page);

        echo $this->view->render('hours', [
            'head' => $head,
            'hours' => $hour->order('number_day')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'paginator' => $pager->render()
        ]);
    }

    /**
     * APP HOUR EDIT VIEW | IT PRESENTS THE CURRENT HOUR EDIT SCREEN
     * @param array|null $data
     * @return void
     */
    public function hour(?array $data): void
    {
        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);
        // META SEO
        $head = $this->seo->make("Horário - ", url('/app/horario'));

        if (empty($id) || empty((new Hour())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Esse horário',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/horarios')
                ]
            ]);
            return;
        }

        echo $this->view->render('hour', [
            'head' => $head,
            'hour' => (new Hour())->findById($id)
        ]);
    }

    /**
     * @return void
     */
    public function createHour(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Horário - ", url());
        echo $this->view->render("creates/hour", [
            "head" => $head
        ]);
    }

    /**
     *  IT UPDATES OR CREATES CURRENT HOUR OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
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
            }
        }

        echo json_encode($json);
    }

    /**
     * IT LOAD HOUR DINAMIC COLUMNS WITH AJAX TO THE FORM INPUT BASED AT DATE INPUT
     * @param array $data
     * @return void
     */
    public function getHour(array $data): void
    {
        // classe responsavel por retornar um horário e dia da semana
        $callback = HourManager::getHourByDate($data);
        echo json_encode($callback);
    }

    /**
     * @param array $data
     * @return void
     */
    public function getList(array $data): void
    {
        $callback = (new Lists())->findByStoreHour($data['id_store'], $data['id_hour'], $data['date_moviment']);
        // O REG EXP SERVE PARA VERIFICAR SE ESTÁ NO MODO EDIÇÃO PARA TRAZER A LISTA MESMO SE JÁ EXISTIR E TIVER LANÇADO
        if (!empty($callback->id) && (new Moviment())->findByIdList($callback->id) && !preg_match('/movimentacao\//i',
                $_SERVER['HTTP_REFERER'])) {
            // JÁ FOI LANÇADO
            $callback = null;
        }
        echo json_encode($callback);
    }

    /**
     * @param array $data
     * @return void
     */
    public function getMoviment(array $data): void
    {
        $callback = (new Moviment())->getMoviment($data);
        $json = null;
        if (!empty($callback)) {
            $json['link'] = url("app/movimentacao/{$callback->id}");
            $json['moviment'] = (array)$callback->data();
            $json['moviment']['list'] = (array)$callback->lists()->data();
            $json['moviment']['hour'] = (array)$callback->hour()->data();
            $json['moviment']['store'] = (array)$callback->store()->data();
        }
        echo json_encode($json);
    }

    /**
     * @param array $data
     * @return void
     */
    public function getStore(array $data): void
    {
        $callback = (new Store())->findById($data['id_store']);
        if (empty($callback)) {
            echo json_encode($callback);
        } else {
            echo json_encode($callback->data());
        }
    }

    /**
     * Codando descobri uma forma mais simples, caso eu venha usar futuramente, vai estar comentado: de luiz para futuras
     * manutenções
     * IT LOAD WEEK DAY WITH AJAX TO FORM
     * @param array|null $data
     * @return void
     * public function getWeekDay(?array $data): void
     * {
     * $id = filter_var($data['id'], FILTER_VALIDATE_INT);
     * $weekDay = (new Hour())->findById($id)->week_day;
     * $callback['week_day'] = $weekDay;
     * echo json_encode($callback);
     * }
     */


    /**
     * IT REMOVES CURRENT HOUR OF TABLE WITH ONE CLICK
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
        $json['redirect'] = url('/app/horarios');
        echo json_encode($json);
    }


    /**
     * IT PRESENTES THE REGISTERS OF LISTS TABLE
     * @param array|null $data
     * @return void
     */
    public function lists(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Listas - ", url('app/listas'));

        // Classes que se responsabilizam pelos filtros e modelos
        list($list, $search, $total) = (new SelfList())->filter($data);

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/listas/')));
        $pager->pager($list->count(), 20, $page);


        echo $this->view->render('lists', [
            'head' => $head,
            'lists' => $list->order('list.date_moviment DESC, s.nome_loja ASC')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'allMoney' => $total,
            'paginator' => $pager->render(),
            'search' => ((object)$search ?? null)
        ]);
    }

    /**
     * APP LIST EDIT VIEW | IT PRESENTS THE CURRENT LIST EDIT SCREEN
     * @param array|null $data
     * @return void
     */
    public function list(?array $data): void
    {
        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);
        // META SEO
        $head = $this->seo->make("Listas - ", url('/app/lista'));

        if (empty($id) || empty((new SelfList())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Essa Lista',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/listas')
                ]
            ]);
            return;
        }

        echo $this->view->render('list', [
            'head' => $head,
            'list' => (new SelfList())->findById($id)
        ]);
    }

    /**
     * @return void
     */
    public function createList(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Lista - ", url());

        echo $this->view->render("creates/lists", [
            "head" => $head,
            "currentHour" => ((new \Source\Models\currentHour())->findById(1))->hour()
        ]);
    }

    /**
     *  IT UPDATES OR CREATES CURRENT LISTS OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
     * @param array $data
     * @return void
     */
    public function saveList(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($data)) {

            // REQUERIDOS
            $required = SelfList::requiredData($data);
            if (!empty($required)) {
                $json['message'] = $required;
                echo json_encode($json);
                return;
            }

            //atualizar
            $list = (new SelfList());
            if (!empty($data['id'])) {
                $list = $list->findById($data['id']);
            }

            // SETS
            $list->id_hour = $data['id_hour'];
            $list->id_store = $data['id_store'];
            $list->value = money_fmt_app($data['total_value']);
            $list->date_moviment = $data['date_moviment'];

            /** @var SelfList $list */
            if (!$list->saveRoutine()) {
                $json['message'] = $list->message()->render();
            } else {
                $json['message'] = $this->message->success("Tudo certo {$this->user->first_name}, a lista atualizado com sucesso!")->render();
                $this->message->success("Tudo certo {$this->user->first_name}, a lista atualizado com sucesso!")->flash();
                $json['reload'] = true;
                $json['scroll'] = 100;
            }
        }

        echo json_encode($json);
    }

    /**
     * IT REMOVES CURRENT LIST OF TABLE WITH ONE CLICK
     * @param array $data
     * @return void
     */
    public function removeList(array $data): void
    {
        $list = (new SelfList())->findById($data['id']);
        if ($list) {
            $list->destroy();
            // RECALCULA A CADA DELETE
            SelfList::calc($list);
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, lista  removidA com sucesso!")->flash();
        $json['redirect'] = url('/app/listas');
        echo json_encode($json);
    }

    /**
     * IT REMOVES ALL LISTS OF TABLE WITH ONE CLICK
     * @param array $data
     * @return void
     */
    public function removeAllLists(array $data): void
    {
        $list = (new Lists())->findById($data['id']);
        if ($list) {
            $list->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, lista de custo removido com sucesso!")->flash();
        $json['redirect'] = url('/app/listas');
        echo json_encode($json);
    }

    /**
     * IT PRESENTES THE REGISTERS OF CASH FLOW TABLE
     * @param array|null $data
     * @return void
     */
    public function cashFlows(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Fluxo - ", url('app/fluxos-de-caixa'));

        list($cashFlows, $search, $total) = (new CashFlow())->filter($data);
        //($moviment = new Moviment())->filter((new Filter($moviment)), $data);

        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/fluxos-de-caixa/')));
        $pager->pager($cashFlows->count(), 20, $page);

        echo $this->view->render('cash-flows', [
            'head' => $head,
            'cashFlows' => $cashFlows->order('cash_flow.id DESC')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'allMoney' => isnt_empty($total, 'self', '0.00'),
            'paginator' => $pager->render(),
            'search' => (object)$search
        ]);
    }

    /**
     * APP CASH FLOW EDIT VIEW | IT PRESENTS THE CURRENT CASH FLOW EDIT SCREEN
     * @param array $data
     * @return void
     */
    public function cashFlow(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Editar lançamento - ", url('/app/fluxo-de-caixa'));

        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);

        if (empty($id) || empty((new CashFlow())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Esse lançamento',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/fluxos-de-caixa')
                ]
            ]);
            return;
        }


        echo $this->view->render('cash-flow', [
            'head' => $head,
            'cash' => (new CashFlow())->findById($id)
        ]);
    }

    /**
     * @return void
     */
    public function createCashFlow(): void
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Fluxo de Caixa - ", url());

        echo $this->view->render("creates/cash-flow", [
            "head" => $head,
            'currentHour' => ((new \Source\Models\currentHour())->findById(1))->hour()
        ]);
    }

    /**
     *  IT UPDATES OR CREATES CURRENT CASH FLOW OF THE TABLE DEPENDING ON IF HAVE ID OR NOT
     * @param array|null $data
     * @return void
     */
    public function saveCashFlow(?array $data): void
    {
        if (!empty($data)) {

            // CASO UM FLUXO DE CAIXA SEJA UMA SAIDA DA LOJA
            if (!empty($data['id_store'])) {
                $store = (new Store())->findById($data['id_store']);
                $store->valor_saldo = money_fmt_app($data['last_value']);
                if (!$store->save()) {
                    $json['message'] = $store->message()->render();
                    return;
                }
            }

            $cash = (new CashFlow());

            if (!empty($data['id'])) {
                $cash = $cash->findById($data['id']);
            }

            $cash->bootstrap(
                $data["date_moviment"],
                (!empty($data["id_store"]) ? $data["id_store"] : null),
                $data["id_hour"],
                $data["description"],
                money_fmt_app($data["value"]),
                $data["type"],
                (!empty($data["id_cost"]) ? $data["id_cost"] : null)
            );

            if (!$cash->save()) {
                $json['message'] = $cash->message()->render();
            } else {
                $json['message'] = $this->message->success("Lançamento atualizado com sucesso!")->render();
                $this->message->success("Lançamento atualizado com sucesso!")->flash();
                $json['reload'] = true;
                $json['scroll'] = 100;
            }
        } else {
            $json['message'] = $this->message->warning("Todos os campos são necessários!")->render();
        }

        echo json_encode($json);
    }

    /**
     * IT REMOVES CURRENT CASH FLOW OF TABLE WITH ONE CLICK
     * @param array $data
     * @return void
     */
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

    /**
     * @param array|null $data
     * @return void
     */
    public function movimentations(?array $data): void
    {
        // META SEO
        $head = $this->seo->make("Movimentação - ", url('/app/movimentacoes'));

        list($moviments, $search, $total) = (new Moviment())->filter($data);
        $page = (!empty($data['page']) ? $data['page'] : 1);

        $pager = (new Pager(url('/app/movimentacoes/')));
        $pager->pager($moviments->count(), 20, $page);
        echo $this->view->render('moviments', [
            'head' => $head,
            'moviments' => $moviments->order('moviment.date_moviment DESC, s.nome_loja ASC')
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            'allMoney' => isnt_empty($total, 'self', '0.00'),
            'paginator' => $pager->render(),
            'search' => (object)$search
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function moviment(array $data): void
    {
        // META SEO
        $head = $this->seo->make("Movimentação - ", url("/app/movimentacao/{$data['id']}"));

        $id = (filter_var($data['id'], FILTER_VALIDATE_INT) ? $data['id'] : null);

        if (empty($id) || empty((new Moviment())->findById($id))) {
            $cafeWeb = (new View());
            echo $cafeWeb->render("not-found", [
                "head" => $head,
                'error' => (object)[
                    'entity' => 'Essa movimentação',
                    'linkTitle' => 'Voltar a navegar',
                    'link' => url('/app/movimentacoes')
                ]
            ]);
            return;
        }

        echo $this->view->render('moviment', [
            'head' => $head,
            'view' => $this->view,
            'moviment' => (new Moviment())->findById($id),
            "currentHour" => ((new \Source\Models\currentHour())->findById(1))->hour()
        ]);

    }

    /**
     * @return void
     */
    public function createMoviment()
    {
        // META SEO
        $head = $this->seo->make("Cadastrar Movimentação - ", url());

        // no banco deve haver o id 1 exclusivamente para o currentHour
        echo $this->view->render("creates/moviment", [
            'view' => $this->view,
            "head" => $head,
            'currentHour' => ((new \Source\Models\currentHour())->findById(1))->hour()
        ]);
    }

    /**
     * @param array|null $data
     * @return void
     */
    public function saveMoviment(?array $data): void
    {
        /** TESTE  $data = [
         * 'date_moviment' => '2022-03-09',
         * 'id_hour' => '107',
         * 'id_store' => '26',
         * 'last_value' => '-1.713,00',
         * 'id_list' => '19',
         * 'net_value' => '0',
         * 'paying_now' => '0',
         * 'expend' => '0',
         * 'get_value' => '0',
         * 'beat_value' => '0',
         * 'new_value' => '0',
         * 'prize' => '0',
         * 'beat_prize' => '0',
         * 'prize_office' => '0',
         * 'prize_store' => '0'
         * ]; */

        if (!empty($data)) {
            // Metodo que realiza toda a regra de negócio e automatização do lançamento de movimento
            (new Moviment())->attach($data, $this->user);
        } else {
            $json['message'] = $this->message->warning("Todos os campos são necessários!")->render();
            echo json_encode($json);
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function removeMoviment(array $data): void
    {
        $moviment = (new Moviment())->findById($data['id']);
        if ($moviment) {
            $moviment->destroy();
        }
        $this->message->success("Tudo pronto {$this->user->first_name}, movimento removido com sucesso!")->flash();
        $json['redirect'] = url('/app/movimentacoes');
        echo json_encode($json);
    }

    /**
     * @param array $data
     * @return void
     */
    public function movimentVerify(array $data): void
    {
        // Se existir um movimento com a mesma data, horario e loja
        // se retornar falso entra aqui
        if (!(new Moviment())->isRepeated($data['date_moviment'], $data['id_hour'], $data['id_store'])) {
            $json['message'] = $this->message->warning('O lançamento já existe.')->render();
            $json['scroll'] = 225;
            echo json_encode($json);
        } else {
            echo json_encode([]);
        }
    }
}

