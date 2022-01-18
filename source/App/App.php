<?php

namespace Source\App;

use Composer\Package\Loader\ValidatingArrayLoader;
use Source\Core\Controller;
use Source\Models\Auth;
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
    public function home()
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
     * APP INCOME (Receber)
     */
    public function income()
    {
        $head = $this->seo->render(
            "Minhas receitas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("income", [
            "head" => $head
        ]);
    }

    /**
     * APP EXPENSE (Pagar)
     */
    public function expense()
    {
        $head = $this->seo->render(
            "Minhas despesas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("expense", [
            "head" => $head
        ]);
    }

    /**
     * APP INVOICE (Fatura)
     */
    public function invoice()
    {
        $head = $this->seo->render(
            "Aluguel - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("invoice", [
            "head" => $head
        ]);
    }

    /**
     * APP LIST USERS
     */
    public function users(array $data)
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
    public function profile(array $data)
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

            if ($data['password'] != $data['password_re']) {
                $json['message'] = $this->message->warning("Informe senhas iguais.")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $user = new User();
            $user = $user->findByEmail($data['email']);

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
    public function logout()
    {
        (new Message())->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }

    public function stores()
    {
        $head = $this->seo->render(
            "Lojas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url('/app/lojas'),
            theme("/assets/images/share.jpg"),
            false
        );
        $store = new Store();
        //$page = (!empty($data['page']) ? $data['page'] : 1);

        //$pager = (new Pager('/arrecadacao/app/usuarios/'));
        //$pager->pager($user->find()->count(), 20, $page);

        echo $this->view->render("stores", [
            "head" => $head,
            'stores' => $store
                ->find()
                ->limit(20)
                ->offset(1)
                ->fetch(true),
            'paginator' => null
        ]);
    }

    public function store(array $data)
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

    public function storeSave(?array $data)
    {
        if (!empty($data['id'])) {
            if (!$id = filter_var($data['id'], FILTER_VALIDATE_INT)) {
                $json['message'] = $this->message->error('Escolha uma loja válida!')->render();
                echo json_encode($json);
                return;
            }

            $store = (new Store())->findByIdStore($id);

            $store->bootstrap(
                $data["nome_loja"],
                $data["valor_saldo"],
                $data["comissao"],
                $data["valor_aluguel"],
                $data["aluguel_dia"],
                $data["valor_gratificacao"],
                $data["gratificacao_dia"]
            );

            if ($store->save()) {
                $json['message'] = $store->message()->success("Loja atualizada com sucesso!")->render();
            } else {
                $json['message'] = $store->message()->render();
            }

        } else {
            $store = (new Store());

            if (!empty($store->findByName($data['nome_loja']))) {
                $json['message'] = $this->message->warning('Essa loja já está cadastrada!')->render();
                echo json_encode($json);
                return;
            }


            $store->bootstrap(
                $data["nome_loja"],
                $data["valor_saldo"],
                $data["comissao"],
                $data["valor_aluguel"],
                $data["aluguel_dia"],
                $data["valor_gratificacao"],
                $data["gratificacao_dia"]
            );

            if ($store->save()) {
                $json['message'] = $store->message()->success("Loja cadastrada com sucesso!")->render();
            } else {
                $json['message'] = $store->message()->render();
            }
        }

        echo json_encode($json);
    }

}