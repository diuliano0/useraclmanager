<?php

namespace BetaGT\UserAclManager\Presenters;

use BetaGT\UserAclManager\Transformers\UserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter
 *
 * @package namespace BetaGT\UserAclManager\Presenters;
 */
class UserPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}
