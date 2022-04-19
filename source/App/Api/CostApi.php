<?php

namespace Source\App\Api;

use Source\Models\Center;

class CostApi extends Api
{

    public function index()
    {
        $costs = (new Center())->find()->fetch(true);
        foreach ($costs as $cost) {
            $data[] = $cost->data();
        }
        $this->back(['costs' => ($data ?? '')]);
    }

    public function create(array $data)
    {
        if (empty($data)) {
            $this->call(
                400,
                'empty_values',
                'Os dados não foram submetidos.'
            )->back();
            return;
        }

        $cost = new Center();

        $cost->description = (!empty($data['description']) ? $data['description'] : null);
        $cost->emit = (!empty($data['emit']) ? $data['emit'] : null);

        if (!$cost->save()) {
            $this->call(
                400,
                'bad_request',
                $cost->message()->getText()
            )->back();
            return;
        }
        $this->back(['cost' => $cost->data()]);

    }

    public function read(array $data)
    {
        if (empty($data['id_cost']) || !$id = filter_var($data['id_cost'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }

        $cost = (new Center())->findById($id);
        if (!$cost) {
            $this->call(
                404,
                'not_found',
                'O Centro de Custo não existe.'
            )->back();
            return;
        }
        $this->back(['cost' => $cost->data()]);
    }

    public function update(array $data)
    {
        $data = array_map('filter_var', $data);
        if (empty($data['id_cost']) || !$id = filter_var($data['id_cost'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }
        $cost = (new Center())->findById($id);

        if (!$cost) {
            $this->call(
                404,
                'not_found',
                'Centro de custo não encontrado.'
            )->back();
            return;
        }

        $cost->description = (!empty($data['description']) ? $data['description'] : null);
        $cost->emit = (!empty($data['emit']) ? $data['emit'] : null);

        if (!$cost->save()) {
            $this->call(
                400,
                'bad_request',
                $cost->message()->getText()
            )->back();
            return;
        }
        $this->back(['cost' => $cost->data()]);
    }

    public function delete(array $data)
    {
        if (empty($data['id_cost']) || !$id = filter_var($data['id_cost'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }
        $cost = (new Center())->findById($id);

        if (!$cost) {
            $this->call(
                404,
                'not_found',
                'Centro de custo não encontrado.'
            )->back();
            return;
        }

        $cost->destroy();

        $this->call(
            200,
            'deleted',
            'Centro de custo deletado com successo.',
            'success'
        )->back();

    }

}