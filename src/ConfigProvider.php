<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Lisettes\Auth;

use Lisettes\Auth\Listener\ValidatorPhoneOrEmailRuleListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        $base_path = BASE_PATH;
        return [
            'dependencies' => [
            ],
            'commands'     => [
            ],
            'listeners' => [
                ValidatorPhoneOrEmailRuleListener::class
            ],
            'annotations'  => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish'      => [
                [
                    'id'          => 'en-US language',
                    'description' => 'The languages for auth.',
                    'source'      => __DIR__ . '/../publish/languages/en_US/auth.php',
                    'destination' => $base_path . '/storage/languages/en_US/auth.php',
                ],
                [
                    'id'          => 'zh-CN language',
                    'description' => 'The languages for auth.',
                    'source'      => __DIR__ . '/../publish/languages/zh_CN/auth.php',
                    'destination' => $base_path . '/storage/languages/zh_CN/auth.php',
                ],
                [
                    'id'          => 'user Model',
                    'description' => 'The model for auth.',
                    'source'      => __DIR__ . '/../publish/models/User.php',
                    'destination' => $base_path . '/app/Model/User.php',
                ],
            ],
        ];
    }
}
