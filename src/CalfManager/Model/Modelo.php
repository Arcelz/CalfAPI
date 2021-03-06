<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 12/09/2017
 * Time: 23:56
 */

namespace CalfManager\Model;

use Exception;

/**
 * Class Modelo
 * @package CalfManager\Model
 */
abstract class Modelo
{
    protected $id;
    /**
     * @var string
     */
    protected $dataAlteracao;
    /**
     * @var string
     */
    protected $dataCriacao;
    /**
     * @var Usuario
     */
    protected $usuarioCadastro;
    /**
     * @var Usuario
     */
    protected $usuarioAlteracao;

    /**
     * @var bool
     */
    protected $status;

    /**
     * @return int|null
     */
    abstract public function cadastrar(): ?int;

    /**
     * @return bool
     * @throws Exception
     */
    abstract public function alterar(): bool;

    /**
     * @param int $page
     * @return array
     * @throws Exception
     */
    abstract public function pesquisar(int $page): array;

    /**
     * @return bool
     * @throws Exception
     */
    abstract public function deletar(): bool;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDataAlteracao(): string
    {
        return $this->dataAlteracao;
    }

    /**
     * @param string $dataAlteracao
     */
    public function setDataAlteracao(string $dataAlteracao): void
    {
        $this->dataAlteracao = $dataAlteracao;
    }

    /**
     * @return string
     */
    public function getDataCriacao(): string
    {
        return $this->dataCriacao;
    }

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao(string $dataCriacao): void
    {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * @return Usuario
     */
    public function getUsuarioCadastro(): Usuario
    {
        if($this->usuarioCadastro == null) {
           return $this->usuarioCadastro = new Usuario();
        }
        return $this->usuarioCadastro;
    }

    /**
     * @param Usuario $usuarioCadastro
     */
    public function setUsuarioCadastro(Usuario $usuarioCadastro): void
    {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    /**
     * @return Usuario
     */
    public function getUsuarioAlteracao(): Usuario
    {
        if($this->usuarioAlteracao == null){
            return $this->usuarioAlteracao = new Usuario();
        }
        return $this->usuarioAlteracao;
    }

    /**
     * @param Usuario $usuarioAlteracao
     */
    public function setUsuarioAlteracao(Usuario $usuarioAlteracao): void
    {
        $this->usuarioAlteracao = $usuarioAlteracao;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}
