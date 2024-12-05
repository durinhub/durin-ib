<?php

namespace App\Enums;

enum AdminRightsEnum: int {
    case DoAll = 0;
    case ChangeRights = 1;
    case CreateBoards = 2;
    case DeleteBoards = 3;
    case ToggleAdmCookie = 4;
    case LimpaCache = 5;
    case SeedDb = 6;
    case MigrateDb = 7;
    case MigrateRefreshDb = 8;
    case ViewPhpInfo = 9;
    case ToggleCaptcha = 10;
    case BlockNewPosts = 11;
    case BypassAdmCookie = 12;
    case NoticiasCrud = 13;
    case ToggleLockUsers = 14;
    case RegisterAdmin = 15;
    case ManageAds = 16;
    case SeeActivityLogs = 17;
    case ApplyFiltersPastPosts = 18;
}