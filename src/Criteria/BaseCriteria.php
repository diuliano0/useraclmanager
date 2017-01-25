<?php

namespace BetaGT\UserAclManager\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;


/**
 * Class criada para fazer consultas na API passando JSON como filtros
 *
 * Para fazer consultas utilize a estruturas descria abaixo:
 *
 * Ex: url teste: http://localhost:8000/api/v1/front/plano/consulta?consulta=
 *                  {"filtro": {"tabela_preco.estados.uf": "TO", "tabela_preco.cidades.titulo" : "Palmas"}}
 *
 * > Filtro Normal
 *    - consulta={"filtro": {"tabela_preco.estados.uf": "TO", "tabela_preco.cidades.titulo" : "Palmas"}}
 *
 * > Filtro Between
 *    - consulta={"filtro": {"plano.created_at": "19/01/2017;20/01/2017",}}
 *
 * > Filtro In
 *    - consulta={"filtro": {"plano.tipo": "anunciante;imobiliaria;qimob-erp",}}
 *
 *
 * Consulta com Filtro:
 *
 *
 * Class BaseCriteria
 * @package BetaGT\UserAclManager\Criteria
 */
abstract class BaseCriteria
{

    const FILTRO_NAME='filtro';

    const GET_HTTP_CONSULTA = "consulta";

    protected $filterCriteria = [];

    protected $defaultTable = [];

    /**
     * @var Request
     */
    protected $request;

    protected $whereArray;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->whereArray = json_decode($this->request->get(self::GET_HTTP_CONSULTA),true);
    }

    public function defaultValidate($whereArray){
        if(is_null($whereArray)){
            return true;
        }

        if(!array_key_exists(self::FILTRO_NAME,$whereArray)){
            return true;
        }

        return false;
    }

    /**
     * Apply criteria in query repository
     *
     * @param       Model              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     *
     * url teste: http://localhost:8000/api/v1/front/plano/consulta?filtro={"tabela_preco.estados.uf": "TO", "tabela_preco.cidades.titulo" : "Palmas"}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $this->defaultTable = array_merge($this->defaultTable,[$model->getTable().'.*']);
        if($this->defaultValidate($this->whereArray)){
            return $model;
        }
        $model = $model->select($this->defaultTable);
        $this->builder($model,$this->whereArray[self::FILTRO_NAME],$this->filterCriteria);
        return $model;
    }

    /**
     *
     * @param $model
     * @param array $array
     * @param $fields
     * @return mixed
     * @throws \Exception
     */
    protected function builder(&$model, array $array, $fields){

        foreach ($array as $key =>$value){
            $table = explode('.',$key);
            if(count($table)!=2){
                continue;
            }
            if(!$this->schemaValidate($table[0],$table[1])){
                continue;
            }

            if(!array_key_exists($key,$fields)){
                continue;
            }

            switch ($fields[$key]){
                case '=':
                    $model->where($key,'=',$value);
                    break;
                case 'like':
                    $model->where($key,'like',"%".$value."%");
                    break;
                case 'between':
                    $data = explode(';',$value);
                    if(count($data)==2){
                        $model->whereBetween($key, [$data[0], $data[1]]);
                    }
                    break;
                case 'in':
                    /**
                     * TODO finalizar
                     */
                    break;
            }

        }
    }

    protected function schemaValidate($table, $column){
        return \Schema::hasColumn($table, $column);
    }

    /**
     * @param $model
     * @param $field
     * @param $order
     */
    protected function builderOrderBy($model, $field, $order){
       return $model->orderBy($field, $order);
    }
}
