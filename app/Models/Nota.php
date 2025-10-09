<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Core\Database;

class Nota
{
    public $id;

    public $idUser;

    public $titulo;

    public $nota;

    public $data_criacao;

    public $data_atualizacao;

    public function dataCriacao()
    {
        return Carbon::parse($this->data_criacao);
    }

    public function dataAtualizacao()
    {
        return Carbon::parse($this->data_atualizacao);
    }

    public function nota()
    {
        if (session()->get('mostrar')) {
            return decrypt($this->nota ?? '');
        }

        return str_repeat('*', strlen($this->nota ?? ''));
    }

    public static function all($pesquisar = null)
    {
        $db = new Database(config('database'));

        return $db->query(
            query: 'select * from notas where idUser = :idUser' . (
                $pesquisar ? 'and titulo like :pesquisar' : null
            ),
            class: self::class,
            params: array_merge(['idUser' => auth()->id], $pesquisar ? ['pesquisar' => "%$pesquisar%"] : [])
        )->fetchAll();
    }

    public static function create($data)
    {
        $database = new Database(config('database'));

        $database->query(
            query: 'insert into notas (idUser, titulo, nota, data_criacao, data_atualizacao)
                values (
                    :idUser,
                    :titulo,
                    :nota,
                    :data_criacao,
                    :data_atualizacao
                )
            ',
            params: array_merge($data, [
                'data_criacao'     => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s'),
            ])
        );
    }

    public static function update($id, $titulo, $nota)
    {
        $db = new Database(config('database'));

        $set = 'titulo = :titulo';

        if ($nota) {
            $set .= ', nota = :nota';
        }

        $db->query(
            query: "
                update notas
                set $set
                where id = :id
            ",
            params: array_merge(
                [
                    'titulo' => $titulo,
                    'id'     => $id,
                ],
                $nota ? ['nota' => encrypt($nota)] : []
            )
        );
    }

    public static function delete($id)
    {
        $db = new Database(config('database'));

        $db->query(
            query: '
                delete from notas
                where id = :id
            ',
            params: [
                'id' => $id,
            ]
        );
    }
}
