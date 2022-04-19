<?php

namespace Source\App\Api;

use Source\Models\Hour;

class HourApi extends Api
{
    public function index()
    {
        $hours = (new Hour())->find()->fetch(true);
        $data = [];
        foreach ($hours as $hour) {
            $data[] = $hour->data();
        }
        $this->back(['hours' => ($data ?? null)]);
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

        $hour = new Hour();

        $hour->number_day = (!empty($data['number_day']) ? $data['number_day'] : null);
        $hour->week_day = (!empty($data['week_day']) ? $data['week_day'] : null);
        $hour->description = (!empty($data['description']) ? $data['description'] : null);

        if (!$hour->save()) {
            $this->call(
                400,
                'bad_request',
                $hour->message()->getText()
            )->back();
            return;
        }
        $this->back(['cost' => $hour->data()]);

    }

    public function read(array $data)
    {
        if (empty($data['id_hour']) || !$id = filter_var($data['id_hour'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }

        $hour = (new Hour())->findById($id);
        if (!$hour) {
            $this->call(
                404,
                'not_found',
                'O Centro de Custo não existe.'
            )->back();
            return;
        }
        $this->back(['hour' => $hour->data()]);
    }

    public function update(array $data)
    {
        if (empty($data)) {
            $this->call(
                400,
                'empty_values',
                'Os dados não foram submetidos.'
            )->back();
            return;
        }

        if (empty($data['id_hour']) || !$id = filter_var($data['id_hour'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }

        $hour = (new Hour())->findById($id);
        if (!$hour) {
            $this->call(
                404,
                'not_found',
                'O horário não existe.'
            )->back();
            return;
        }

        $hour->number_day = (!empty($data['number_day']) ? $data['number_day'] : null);
        $hour->week_day = (!empty($data['week_day']) ? $data['week_day'] : null);
        $hour->description = (!empty($data['description']) ? $data['description'] : null);

        if (!$hour->save()) {
            $this->call(
                400,
                'bad_request',
                $hour->message()->getText()
            )->back();
            return;
        }
        $this->back(['hour' => $hour->data()]);
    }

    public function delete(array $data)
    {
        if (empty($data['id_hour']) || !$id = filter_var($data['id_hour'], FILTER_VALIDATE_INT)) {
            $this->call(
                400,
                'not_int',
                'Envie um id válido.'
            )->back();
            return;
        }
        $hour = (new Hour())->findById($id);

        if (!$hour) {
            $this->call(
                404,
                'not_found',
                'Horário não encontrado.'
            )->back();
            return;
        }

        $hour->destroy();

        $this->call(
            200,
            'deleted',
            'Horário deletado com successo.',
            'success'
        )->back();
    }
}