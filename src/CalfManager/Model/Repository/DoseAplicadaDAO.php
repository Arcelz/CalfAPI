<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 10/09/2018
 * Time: 21:59
 */

namespace CalfManager\Model\Repository;


use CalfManager\Model\Modelo;
use CalfManager\Model\Repository\Entity\DoseAplicadaEntity;
use Exception;

class DoseAplicadaDAO implements IDAO
{
    public function create($obj): ?int
    {
        $entity = new DoseAplicadaEntity();
        $entity->dose = $obj->getDose();
        $entity->medicamento_id = $obj->getMedicamento()->getId();

        $entity->data_cadastro = $obj->getDataCadastro();
        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->usuario_cadastro = $obj->getUsuario()->getId();
        try{
            if($entity->save()){
                return $entity->id;
            }
        }catch (Exception $e) {
            throw new Exception("Erro ao cadastrar uma dose aplicada. Mensagem: " . $e->getMessage());
        }

    }

    public function update($obj): bool
    {
        $entity = new DoseAplicadaEntity();
        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->usuario_alteracao = $obj->getUsuario()->getId();
        if (!is_null($obj->getDose())){
            $entity->dose = $obj->getDose();
        }
        if(!is_null($obj->getMedicamento()->getId())) {
            $entity->medicamento_id = $obj->getMedicamento()->getId();
        }

        try{
            if($entity->save()){
                return $entity->id;
            }
        }catch (Exception $e) {
            throw new Exception("Erro ao alterar uma dose aplicada. Mensagem: " . $e->getMessage());
        }
    }

    public function retreaveAll(int $page): array
    {
        $entity = LaboratorioEntity::ativo();
        $doses = $entity->with('medicamento')
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
        return ["doses_aplicadas" => $doses];
    }

    public function retreaveById(int $id): array
    {
        $entity = LaboratorioEntity::ativo();
        $dose = $entity->with('medicamento')
            ->where('id', $id)
            ->first()
            ->toArray();
        return ["doses_aplicadas" => $dose];
    }

    public function retreaveByIdMedicamento(int $idmedicamento,int $page): array
    {
        $entity = LaboratorioEntity::ativo();
        $doses = $entity->with('medicamento')
            ->where('medicamento_id', $idmedicamento)
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
        return ["doses_aplicadas" => $doses];
    }



    public function delete(int $id): bool
    {
        try {
            $entity = DoseAplicadaEntity::find($id);
            $entity->status = 0;
            if ($entity->save()) {
                return true;
            };
        } catch (Exception $e) {
            throw new Exception("Erro ao excluir uma dose aplicada. Mensagem: " . $e->getMessage());
        }
    }

}