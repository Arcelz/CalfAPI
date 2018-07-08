<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 22/02/2018
 * Time: 11:21
 */

namespace CalfManager\Model\Repository;


use Exception;
use CalfManager\Model\Doenca;
use CalfManager\Model\Repository\Entity\AnimalHasDoencaEntity;
use CalfManager\Model\Repository\Entity\DoencaEntity;
use CalfManager\Utils\Config;

class DoencaDAO implements IDAO
{

    /**
     * @param Doenca $obj
     * @return int|null
     * @throws Exception
     */
    public function create($obj): ?int
    {
        try {
            $entity = new DoencaEntity();
            $entity->nome = $obj->getNome();
            $entity->descricao = $obj->getDescricao();
            $entity->data_cadastro = $obj->getDataCriacao();
            $entity->data_alteracao = $obj->getDataAlteracao();
            $entity->usuario_cadastro = $obj->getUsuarioCadastro()->getId();

            if ($entity->save()) {
                return true;
            }

        } catch (Exception $e) {
            throw new Exception("Erro ao tentar salvar uma nova Doença. " . $e->getMessage());
        }
        return false;
    }

    /**
     * @param Doenca $obj
     * @return bool
     * @throws Exception
     */
    public function update($obj): bool
    {
        $entity = DoencaEntity::find($obj->getId());
        $entity->usuario_alteracao = $obj->getUsuarioAlteracao()->getId();
        if (!is_null($obj->getNome())) {
            $entity->nome = $obj->getNome();
        }
        if (!is_null($obj->getDescricao())) {
            $entity->descricao = $obj->getDescricao();
        }
        if (!is_null($obj->getSituacao())) {
            $entity->situacao = $obj->getSituacao();
        }
        try {
            if ($entity->save()) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao tentar alterar uma doença. " . $e->getMessage());
        }
        return false;
    }

    /**
     * @param int $page
     * @return array
     */
    public function retreaveAll(int $page): array
    {
        return [
            "doencas" => DoencaEntity
                ::ativo()
                ->paginate(
                    Config::QUANTIDADE_ITENS_POR_PAGINA,
                    ['*'],
                    'pagina',
                    $page
                )];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function retreaveById(int $id): array
    {
        try {
            return [
                "animais" => DoencaEntity
                    ::ativo()
                    ->where('id', $id)
                    ->get()
            ];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por ID" . $e->getMessage());
        }
    }

    /**
     * @param string $nome
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function retreaveByNome(string $nome, int $page): array
    {
        try {
            return [
                "animais" => DoencaEntity
                    ::ativo()
                    ->where('nome', 'like', $nome . "%")
                    ->paginate(Config::QUANTIDADE_ITENS_POR_PAGINA, ['*'], 'pagina', $page)
            ];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por nome" . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            $entity = DoencaEntity::find($id);
            $entity->status = 0;
            if ($entity->save()) {
                return true;
            };
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar desativar uma fazenda" . $e->getMessage());
        }
        return false;
    }

    /**
     * @param $idAnimal
     * @param $situacao
     * @param $idDoenca
     * @return bool
     * @throws Exception
     */
    public function adoecer($idAnimal, $situacao, $idDoenca)
    {
        if (count((new AnimalDAO())->retreaveById($idAnimal)) == 0)
            throw new Exception("Não foi possível adoecer este animal pois ele nao existe");
        if (count($this->retreaveById($idDoenca)) == 0)
            throw new Exception("Não foi possível adoecer este animal pois esta doenca nao existe");
        $entity = new AnimalHasDoencaEntity();
        $entity->animais_id = $idAnimal;
        $entity->doencas_id = $idDoenca;
        $entity->situacao = $situacao;
        return $entity->save();
    }
}