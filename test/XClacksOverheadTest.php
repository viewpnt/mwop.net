<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

namespace MwopTest;

use Mwop\XClacksOverhead;
use PHPUnit\Framework\TestCase;

class XClacksOverheadTest extends TestCase
{
    use HttpMessagesTrait;

    public function testMiddlewareInjectsResponseReturnedByNextWithXClacksOverheadHeader()
    {
        $middleware = new XClacksOverhead();
        $request = $this->createRequestMock()->reveal();
        $response = $this->createResponseMock();
        $response
            ->withHeader('X-Clacks-Overhead', 'GNU Terry Pratchett')
            ->will([$response, 'reveal'])
            ->shouldBeCalled();
        $delegate = $this->delegateShouldExpectAndReturn(
            $response->reveal(),
            $request
        );

        $this->assertSame(
            $response->reveal(),
            $middleware->process($request, $delegate)
        );
    }
}
