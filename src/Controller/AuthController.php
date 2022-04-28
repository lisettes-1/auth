<?php

declare(strict_types=1);

namespace Lisettes\Auth\Controller;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Firebase\JWT\JWT;
use Hyperf\Di\Annotation\Inject;
use Lisettes\Auth\Dao\UserDAO;
use Lisettes\Auth\Request\Auth;
use Lisettes\Utils\Abstracts\AbstractController;
use Lisettes\Utils\Exception\Response\ErrorResponse;
use Lisettes\Utils\Exception\Response\SuccessResponse;

/**
 * AuthController.
 */
class AuthController extends AbstractController
{
    /**
     * @Inject()
     * @var UserDAO
     */
    protected UserDAO $DAO;

    /**
     * 密码登录
     */
    public function password()
    {
        $request = di(Auth::class);
        $request->scene('password-login')->validateResolved();
        $data = $request->validated();

        $account = trim($data['account']);
        if ((new EmailValidator())->isValid($account, new RFCValidation())) {
            $user = $this->DAO->findFromEmail($account);
        }
        else {
            $user = $this->DAO->findFromPhone($account);
        }
        if (!password_verify(trim($data['password']), $user->password)) {
            throw new ErrorResponse(trans('auth.messages.login-password-error'));
        }
        throw new SuccessResponse([
            'token' => JWT::encode([
                'iss' => '',
                'iat' => time(),
                'exp' => time() + config('system.authorization_expire', 86400 * 30),
                'uid' => $user->id
            ], config('system.jwt_key')),
            'info'  => $user->getInfo(),
        ]);
    }

}
