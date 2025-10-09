<?php

declare(strict_types=1);

namespace Core;

class Validacao
{
    public $validacoes = [];

    public static function validar($regras, $dados)
    {
        $validacao = new self();

        foreach ($regras as $campo => $regrasDoCampo) {
            foreach ($regrasDoCampo as $regra) {
                $valorDoCampo = $dados[$campo];

                if ($regra == 'confirmed') {
                    $validacao->$regra($campo, $valorDoCampo, $dados["{$campo}_confirm"]);
                } elseif (str_contains($regra, ':')) {
                    $temp = explode(':', $regra);

                    $regra = $temp[0];

                    $regraAr = $temp[1];

                    $validacao->$regra($regraAr, $campo, $valorDoCampo);
                } else {
                    $validacao->$regra($campo, $valorDoCampo);
                }
            }
        }

        return $validacao;
    }

    private function required($campo, $valor)
    {
        if (strlen($valor) == 0) {
            $this->addError($campo, "O campo é obrigatório.");
        }
    }

    private function email($campo, $valor)
    {
        if (! filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->addError($campo, "O $campo é inválido.");
        }
    }

    private function confirmed($campo, $valor, $valorDeConfirmacao)
    {
        if ($valor != $valorDeConfirmacao) {
            $this->addError($campo, "O $campo de confirmação está diferente.");
        }
    }

    private function unique($tabela, $campo, $valor)
    {
        if (strlen($valor) == 0) {
            return;
        }

        $db = new Database(config('database'));

        $resultado = $db->query(
            query: "select * from $tabela where $campo = :valor",
            params: ['valor' => $valor]
        )->fetch();

        if ($resultado) {
            $this->addError($campo, "O $campo já está sendo usado.");
        }
    }

    private function min($min, $campo, $valor)
    {
        if (strlen($valor) < $min) {
            $this->addError($campo, "O $campo precisa ter um mínimo de $min caracteres.");
        }
    }

    private function max($max, $campo, $valor)
    {
        if (strlen($valor) > $max) {
            $this->addError($campo, "O $campo precisa ter um máximo de $max caracteres.");
        }
    }

    private function strong($campo, $valor)
    {
        if (! strpbrk($valor, "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~")) {
            $this->addError($campo, "A $campo precisa um caractere especial nela.");
        }
    }

    private function addError($campo, $erro)
    {
        $this->validacoes[$campo][] = $erro;
    }

    public function naoPassou($nomeCustomizado = null)
    {
        $chave = 'validacoes';

        if ($nomeCustomizado) {
            $chave .= '_' . $nomeCustomizado;
        }

        flash()->push($chave, $this->validacoes);

        return count($this->validacoes) > 0;
    }

    public function erros(): array
    {
        return $this->validacoes;
    }
}
