<?php
/**
 * fishconnect
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Http\Request\Handler;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\RequestHandlerInterface;

class UserRegisterRequestHandler extends HttpFoundationRequestHandler implements RequestHandlerInterface
{
    public function isFileUpload($data)
    {
        return false;
    }
}