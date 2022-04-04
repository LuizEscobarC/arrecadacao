<?php

namespace Source\App\Api;

use Source\Models\User;

class UserApi extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $users = (new User())->find()->fetch(true);
        foreach ($users as $user) {
            $data[] = $user->data();
        }
        $this->back(['users' => $data]);
    }

    public function create(array $data)
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if (empty($data['password']) || empty('password_re')) {
            $this->call(
                400,
                'invalid_values',
                'Senha e repetir senha são obrigatórios!'
            )->back();
            return;
        }

        if ($data['password'] !== $data['password_re']) {
            $this->call(
                400,
                'invalid_values',
                'Senha e repetir senha devem ser identicas!'
            )->back();
            return;
        }

        $user = new User();

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if (!$user->save()) {
            $this->call(
                400,
                'invalid_values',
                $user->message()->getText()
            )->back();
            return;
        }

        $this->back(['response' => $user->data()]);
    }

    public function read(array $data)
    {
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'invalid_values',
                'O usuário que tentou acessar não existe.',
            )->back();
            return;
        }
        $user = (new User())->findById($data['user_id']);
        if (!$user) {
            $this->call(
                404,
                'not_found',
                'O usuário que tentou acessar não existe.',
            )->back();
            return;
        }
        $this->back(['user' => $user->data()]);
    }

    public function update(array $data): void
    {
        if (!$this->requestLimit('updateUser', 3, 60)) {
            $this->call(
                400,
                'request_limit',
                'Aguarde 60 segundos para voltar a requisitar.'
            )->back();
            return;
        }

        if (!empty($data['password']) && $data['password'] !== $data['password_re']) {
            $this->call(
                400,
                'invalid_values',
                'Senha e repetir senha devem ser identicas!'
            )->back();
            return;
        }

        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT) || !$user = (new user())->findById($data['user_id'])) {
            $this->call(
                400,
                'invalid_values',
                'O usuário que tentou atualizar é inexistente.'
            )->back();
            return;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user->first_name = (!empty($data['first_name']) ? $data['first_name'] : $user->first_name);
        $user->last_name = (!empty($data['last_name']) ? $data['last_name'] : $user->last_name);
        $user->email = (!empty($data['email']) ? $data['email'] : $user->email);
        $user->password = (!empty($data['password']) ? $data['password'] : $user->password);
        $user->document = (!empty($data['first_name']) ? $data['first_name'] : $user->document);

        if (! $user->save()) {
            $this->call(
                400,
                'invalid_values',
                $user->message()->getText()
            )->back();
            return;
        }
        $this->call(
            200,
            'updated',
            $user->message()->getText(),
            'success'
        )->back(['user' => $user->data()]);
    }

    public function delete(array $data): void
    {
        if (!$this->requestLimit('deleteUser', 3, 60)) {
            $this->call(
                400,
                'request_limit',
                'Aguarde 60 segundos para voltar a requisitar.'
            )->back();
            return;
        }

        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT) || !$user = (new User())->findById($data['user_id'])) {
            $this->call(
                400,
                'invalid_values',
                'O usuário que tentou deletar é inexistente.'
            )->back();
            return;
        }

        if (!$user->destroy()) {
            $this->call(
                400,
                'bad_request',
                $user->message()->getText()
            )->back();
        }

        $this->call(
            200,
            'deleted',
            $user->message()->getText(),
            'success'
        )->back();
    }
}