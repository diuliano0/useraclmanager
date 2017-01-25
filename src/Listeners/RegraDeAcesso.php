<?php

namespace BetaGT\UserAclManager\Listeners;

use BetaGT\UserAclManager\Events\UsuarioCadastrado;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegraDeAcesso
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UsuarioCadastrado  $event
     * @return void
     */
    public function handle(UsuarioCadastrado $event)
    {
       $role = $event->getRole();
       if(empty($role)){
           return null;
       }

       return $event->getUser()->assignRole($role);
    }
}
