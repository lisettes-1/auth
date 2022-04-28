<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @property int    $id
 * @property string $country         国家
 * @property int    $level           等级
 * @property string $email           邮箱
 * @property string $phone           手机
 * @property string $password        登录密码
 * @property string $trade_password  交易密码
 * @property string $nickname        昵称
 * @property string $avatar          头像
 * @property string $wechat          微信
 * @property string $qq              QQ
 * @property int    $gender          性别
 * @property string $birthday        生日
 * @property string $signature       个性签名
 * @property int    $reg_time        注册时间
 * @property string $reg_ip          注册IP
 * @property string $region          地区
 * @property int    $status          状态
 * @property string $last_login_time 最后登录时间
 * @property string $last_login_ip   最后登录IP
 */
class User extends Model implements CacheableInterface
{
    use Cacheable;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'              => 'integer',
        'level'           => 'integer',
        'gender'          => 'integer',
        'reg_time'        => 'integer',
        'status'          => 'integer',
        'last_login_time' => 'integer'
    ];
    /**
     * @var string[]
     */
    protected $hidden = [
        'password',
        'trade_password',
        'status'
    ];

    public function deleteCache(): bool
    {
        di(EventDispatcherInterface::class)->dispatch(new DeleteListenerEvent('UserUpdate', [
            'id' => $this->id,
        ]));
        di(EventDispatcherInterface::class)->dispatch(new DeleteListenerEvent('UserFindFromEmailUpdate', [
            'email' => $this->email,
        ]));
        di(EventDispatcherInterface::class)->dispatch(new DeleteListenerEvent('UserFindFromPhoneUpdate', [
            'phone' => $this->phone,
        ]));
        return true;
    }

    /**
     * 获取头像
     *
     * @param $value
     *
     * @return string
     */
    public function getAvatarAttribute($value): ?string
    {
        return ($value === null || strpos($value, 'http') !== false) ? $value : (config('system.static_host') . $value);
    }

    /**
     * 注册时间
     *
     * @param $value
     *
     * @return string
     */
    public function getRegTimeAttribute($value): ?string
    {
        return date('Y-m-d H:i', $value);
    }

    /**
     * 获取地区
     *
     * @param $value
     *
     * @return false|string[]
     */
    public function getRegionAttribute($value)
    {
        return $value ? explode('@', $value) : [];
    }

    /**
     * 设置地区
     *
     * @param $value
     */
    public function setRegionAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['region'] = implode('@', $value);
        }
    }

    /**
     * 设置密码
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    /**
     * 设置交易密码
     *
     * @param $value
     */
    public function setTradePasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['trade_password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    /**
     * 获取用户资料
     *
     * @return array
     */
    public function getInfo(): array
    {
        return [
            'uid'             => $this->id,
            'country'         => $this->country,
            'level'           => $this->level,
            'email'           => $this->email ?? '',
            'phone'           => $this->phone ?? '',
            'nickname'        => $this->nickname ?? '',
            'avatar'          => $this->avatar,
            'wechat'          => $this->wechat ?? '',
            'qq'              => $this->qq ?? '',
            'gender'          => $this->gender,
            'birthday'        => $this->birthday ?? '',
            'signature'       => $this->signature ?? '',
            'reg_time'        => $this->reg_time,
            'region'          => $this->region,
            'last_login_time' => $this->last_login_time,
            'last_login_ip'   => $this->last_login_ip
        ];
    }
}