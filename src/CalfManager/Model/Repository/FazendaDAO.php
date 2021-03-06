<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 12:04
 */

namespace CalfManager\Model\Repository;

use Exception;
use CalfManager\Model\Fazenda;
use CalfManager\Model\Repository\Entity\FazendaEntity;
use CalfManager\Model\Repository\Entity\FuncionarioEntity;
use CalfManager\Model\Repository\Entity\AnimalEntity;
use CalfManager\Model\Repository\Entity\LoteEntity;
use CalfManager\Utils\Config;

class FazendaDAO implements IDAO
{

//    TODO: Criar retreaveAllAnimais para retornar todos os animais daquela fazenda.
//    TODO: Criar retreaveAllLotes para retornar todos os lotes daquela fazenda.
    /**
     * @param Fazenda $obj
     * @return int|null
     * @throws Exception
     */
    public function create($obj): ?int
    {
        $entity = new FazendaEntity();
        $entity->nome = $obj->getNome();

        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->data_cadastro = $obj->getDataCriacao();
        $entity->usuario_cadastro = $obj->getUsuarioCadastro()->getId();
        try {
            if ($entity->save()) {
                return $entity->getKey();
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao tentar salvar uma nova fazenda.");
        }
        return false;
    }

    /**
     * @param Fazenda $obj
     * @return bool
     */
    public function update($obj): bool
    {
        $entity = FazendaEntity::find($obj->getId());

        $entity->usuario_alteracao = $obj->getUsuarioAlteracao()->getId();
        $entity->data_alteracao = $obj->getDataAlteracao();
        if (!is_null($obj->getNome())) {
            $entity->nome = $obj->getNome();
        }
        try{
            if($entity->save()){
                return $entity->getKey();
            }
        }catch (Exception $e){
            throw new Exception("Erro ao alterar fazenda".$e->getMessage());
        }

    }

    /**
     * @param int $page
     * @return array
     */
    public function retreaveAll(int $page): array
    {
        $fazenda = FazendaEntity::ativo()->with('lote')
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
        return ["fazendas" => $fazenda];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function retreaveById(int $id): array
    {
        try {
            $fazenda = FazendaEntity::ativo()->with('lote')
                ->where('id', $id)
                ->first()
                ->toArray();
            return[ "fazendas" => $fazenda];
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
                "fazendas" => FazendaEntity::ativo()->with('lote')
                    ->where('nome', 'like', "%". $nome . "%")
                    ->paginate
                    (
                        Config::QUANTIDADE_ITENS_POR_PAGINA,
                        ['*'],
                        'pagina',
                        $page
                    )
            ];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por nome" . $e->getMessage());
        }
    }
    public function retreaveAllAnimais(int $page){

    }
    
    public function possuiReferencias($fazendaId): bool
    {
        $qtdAnimais = AnimalEntity::ativo()->where('fazendas_id', $fazendaId)->get()->count();
        $qtdLotes = LoteEntity::ativo()->where('fazenda_id', $fazendaId)->get()->count();
        $qtdFuncionarios = FuncionarioEntity::ativo()->where('fazenda_id', $fazendaId)->get()->count();
        if($qtdAnimais > 0 || $qtdLotes > 0 ||  $qtdFuncionarios > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            if($this->possuiReferencias($id)){
                throw new Exception("Esta fazenda possui referencias");
            }
            $entity = FazendaEntity::find($id);
            $entity->status = 0;
            if ($entity->save()) {
                return true;
            };
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar desativar uma fazenda. " . $e->getMessage(), 400);
        }
        return false;
    }


}