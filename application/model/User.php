<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 10/14/18
 * Time: 12:58 PM
 */

namespace app\model;

use think\Model;
use think\Config;

class User extends Model {
    protected $pk = 'id';

    protected $table = 'users';

    public static function encryptPassword($pwd) {
        $salt = Config::get('custom.HASH_PASSWORD_KEY');
        return self::hashCreate('sha1', $pwd, $salt);
    }

    /**
     * @param string $algo The algorithm (sha256, sha1, whirlpool, etc)
     * @param string $data The data to encode
     * @param string $salt The salt (This should be the same throughout the system probably)
     * @return string The hashed/salted data  ( C('HASH_PASSWORD_KEY') )
     */
    private static function hashCreate($algo, $data, $salt) {
        $context = \hash_init($algo, HASH_HMAC, $salt);
        hash_update($context, $data);
        return hash_final($context);
    }
}