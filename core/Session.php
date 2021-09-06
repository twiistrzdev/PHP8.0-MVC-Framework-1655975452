<?php

namespace app\core;

/**
 * Class Session
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Session
{
    protected const FLASH_KEY = 'flash_messages';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['toRemove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    /**
     * Session flash setter.
     *
     * @param string $key
     * @param string $message
     */
    public function setFlash(string $key, string $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'toRemove' => false,
            'value' => $message
        ];
    }

    /**
     * Session flash getter.
     *
     * @param string $key
     * @return string|false
     */
    public function getFlash(string $key): string|false
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    /**
     * Session destructor.
     */
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['toRemove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
