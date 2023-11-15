<?php

namespace App\Tests\Security;

use App\Security\AppCustomAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class AppCustomAuthenticatorTest extends TestCase
{
    public function testAuthenticate(): void
    {
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $authenticator = new AppCustomAuthenticator($urlGeneratorMock);

        $expected = new Passport(
            new UserBadge('email@email.com'),
            new PasswordCredentials('123123123'),
            [
                new CsrfTokenBadge('authenticate', 'asdjasdhkasd12y93ykhkaskuaweih213'),
                new RememberMeBadge(),
            ]
        );

        $requestMock = $this->createMock(Request::class);
        $inputBagMock = $this->createMock(ParameterBag::class);
        $inputBagMock->expects($this->exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls('email@email.com', '123123123', 'asdjasdhkasd12y93ykhkaskuaweih213');

        $requestMock->request = $inputBagMock;

        $result = $authenticator->authenticate($requestMock);

        $this->assertEquals($expected, $result);
    }

    public function testOnAuthenticationSuccess(): void
    {
        $expected = new RedirectResponse('dummy');

        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $urlGeneratorMock->expects($this->once())
            ->method('generate')
            ->with('app_dashboard')
            ->willReturnOnConsecutiveCalls('dummy');

        $requestMock = $this->createMock(Request::class);
        $tokenMock = $this->createMock(TokenInterface::class);

        $authenticator = new AppCustomAuthenticator($urlGeneratorMock);
        $result = $authenticator->onAuthenticationSuccess($requestMock, $tokenMock, 'dummy');
        $this->assertEquals($expected, $result);
    }

    public function testOnAuthenticationTargetPathSuccess(): void
    {
        $expected = new RedirectResponse('targetPath');

        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $urlGeneratorMock->expects($this->never())->method('generate');

        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->expects($this->once())
            ->method('get')
            ->willReturnOnConsecutiveCalls('targetPath');

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->once())
            ->method('getSession')
            ->willReturnOnConsecutiveCalls($sessionMock);
        $tokenMock = $this->createMock(TokenInterface::class);

        $authenticator = new AppCustomAuthenticator($urlGeneratorMock);
        $result = $authenticator->onAuthenticationSuccess($requestMock, $tokenMock, 'dummy');

        $this->assertEquals($expected, $result);
    }
}
