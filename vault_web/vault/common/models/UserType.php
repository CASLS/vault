<?php

namespace common\models;

class UserType extends \common\models\base\UserTypeBase
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const USER = 3;
    const EDITOR = 4;
}