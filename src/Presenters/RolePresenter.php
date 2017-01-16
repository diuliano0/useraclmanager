<?php

namespace BetaGT\UserAclManager\Presenters;

use BetaGT\UserAclManager\Transformers\RoleTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RolePresenter
 *
 * @package namespace BetaGT\UserAclManager\Presenters;
 */
class RolePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RoleTransformer();
    }
}
