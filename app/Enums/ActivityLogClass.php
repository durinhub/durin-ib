<?php

namespace App\Enums;

enum ActivityLogClass: int {
    case Info = 0;
    case Erro = 1;
    case Debug = 2;
}