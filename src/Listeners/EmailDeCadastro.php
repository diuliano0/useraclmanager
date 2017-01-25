<?php

namespace Portal\Listeners;

use Portal\Events\UsuarioCadastrado;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Portal\Services\MailService;

class EmailDeCadastro
{
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     *
     * @param  UsuarioCadastrado  $event
     * @return void
     */
    public function handle(UsuarioCadastrado $event)
    {
        $data['name'] = $event->getUser()->name;
        $data['email'] = $event->getUser()->email;
        return $this->mailService->queue($event->getUser()->email,'Comfirmação de cadastro','emails.user.cadastro',$data);
    }
}
