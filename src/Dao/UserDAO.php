<?php

declare(strict_types=1);

namespace Lisettes\Auth\Dao;

use App\Model\User;

use Hyperf\Cache\Annotation\Cacheable;
use Lisettes\Utils\Exception\LogicException;

/**
 * UserDAO.
 */
class UserDAO
{
    /**
     * 通过手机查找用户
     *
     * @Cacheable(prefix="FindFromPhone", value="#{phone}", ttl=30, listener="UserFindFromPhoneUpdate")
     * @param string $phone
     * @param bool   $throw
     *
     * @return mixed
     */
    public function findFromPhone(string $phone, bool $throw = true): ?User
    {
        $data = User::where('phone', $phone)->first();
        if (!$data && $throw) {
            throw new LogicException(trans('auth.messages.phone-not-found'));
        }
        return $data;
    }

    /**
     * 通过邮箱查找用户
     *
     * @Cacheable(prefix="FindFromEmail", value="#{email}", ttl=30, listener="UserFindFromEmailUpdate")
     * @param string $email
     * @param bool   $throw
     *
     * @return mixed
     */
    public function findFromEmail(string $email, bool $throw = true): ?User
    {
        $data = User::where('email', $email)->first();
        if (!$data && $throw) {
            throw new LogicException(trans('auth.messages.email-not-found'));
        }
        return $data;
    }
}
