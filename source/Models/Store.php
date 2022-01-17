<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Support\Email;

class Store extends Model
{

    public function __construct()
    {
        parent::__construct('loja', ['id', 'created_at', 'updated_at'], [
            'nome_loja',
            'valor_saldo',
            'comissao',
            'valor_aluguel',
            'aluguel_dia',
            'valor_gratificacao',
            'gratificacao_dia'
        ]);
    }

    public function bootstrap(
        string $nameStore,
        string $value,
        string $comission,
        string $valueRent,
        string $dayRent,
        string $rentGratification,
        string $dayGratification
    ): Store {
        $this->nome_loja = $nameStore;
        $this->valor_saldo = $value;
        $this->comissao = $comission;
        $this->valor_aluguel = $valueRent;
        $this->aluguel_dia = $dayRent;
        $this->valor_gratificacao = $rentGratification;
        $this->gratificacao_dia = $dayGratification;
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @return null|mixed|Store
     */
    public function findByIdStore(int $id, string $columns = "*"): ?Store
    {
        $find = $this->find("id = :id", "id={$id}", $columns);
        return $find->fetch();
    }

    public function findByName(string $name, string $columns = "*"): ?Store
    {
        $find = $this->find("nome_loja = :nome_loja", "nome_loja={$name}", $columns);
        return $find->fetch();
    }

    public function user(): ?User
    {
        $session = (new Session());
        if (!$session->has('authUser')) {
            return null;
        }
        return (new User())->findById($session->authUser);
    }


}