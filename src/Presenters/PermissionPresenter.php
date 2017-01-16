<?php

namespace BetaGT\UserAclManager\Presenters;

use BetaGT\UserAclManager\Transformers\PermissionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PermissionPresenter
 *
 * @package namespace BetaGT\UserAclManager\Presenters;
 */
class PermissionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PermissionTransformer();
    }
}
