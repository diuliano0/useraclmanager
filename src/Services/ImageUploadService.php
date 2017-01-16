<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 12/01/2017
 * Time: 10:52
 */

namespace BetaGT\UserAclManager\Services;



use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class ImageUploadService
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function upload($field,$path,&$data){
        $request = &$data;
        if($this->request->hasFile($field)){
            if(!$this->request->imagem->isValid()){
                Throw new InvalidParameterException('Ocorreu um erro ao realizar o upload');
            }
            $file  = $this->request->file($field);
            $filename = md5(time()).'.'.$file->getClientOriginalExtension();
            $this->request->imagem->move($path,$filename);
            $request['imagem'] = $filename;
        }
        return null;
    }
}