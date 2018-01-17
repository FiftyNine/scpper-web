<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewUserActivity;

/**
 * Description of DbSelectColumns
 *
 * @author Alexander
 */
class DbSelectColumns 
{
    const USER = [
        DbViewUsers::USERID,
        DbViewUsers::WIKIDOTNAME,
        DbViewUsers::DISPLAYNAME,
        DbViewUsers::DELETED
    ];
    
    const MEMBERSHIP = [
        DbViewMembership::USERID,
        DbViewMembership::SITEID,
        DbViewMembership::JOINDATE
    ];
    
    const USER_ACTIVITY = [
        DbViewUserActivity::USERID,
        DbViewUserActivity::SITEID,
        DbViewUserActivity::VOTES,
        DbViewUserActivity::REVISIONS,
        DbViewUserActivity::PAGES,
        DbViewUserActivity::LASTACTIVITY,
    ];
}
