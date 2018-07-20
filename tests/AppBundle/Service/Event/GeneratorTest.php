<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.07.18
 * Time: 13:10
 */

namespace Tests\AppBundle\Service\Event;


use AppBundle\Service\Event\Generator;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class GeneratorTest extends WebTestCase
{
    public function testCodeGenerator()
    {
        $test = new Generator();
        self::assertNotNull($test->codeGenerator());

    }
}