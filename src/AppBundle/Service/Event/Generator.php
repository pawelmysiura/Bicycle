<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.07.18
 * Time: 18:55
 */

namespace AppBundle\Service\Event;


class Generator
{
    public function codeGenerator()
    {
        $random = random_bytes(30);
        return base64_encode(bin2hex($random));
    }
}