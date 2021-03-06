<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 28/08/2018
 * Time: 21:52
 */

namespace CalfManager\Model\Repository\Entity;


/**
 * @property mixed id
 * @property int status
 * @property mixed usuario_cadastro
 * @property string data_cadastro
 * @property mixed prescricao
 * @property mixed nome
 */
class MedicamentoEntity extends CalfEntity
{
    protected $table = "medicamentos";
    protected $fillable = [
        'id',
        'nome',
        'prescricao',
        'data_alteracao',
        'data_cadastro',
        'usuario_alteracao',
        'usuario_cadastro',
        'status'
    ];
    public function laboratorio() {
        return $this->hasMany('CalfManager\Model\Repository\Entity\LaboratorioEntity');
    }
}