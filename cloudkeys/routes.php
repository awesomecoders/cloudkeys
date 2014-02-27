<?php

$routes = array(
    '|/$|' => 'OverviewHandler'
  , '|/u/([0-9]+)/ajax$|' => 'AjaxHandler'
  , '|/u/([0-9]+)/overview$|' => 'OverviewHandler'
  , '|/overview$|' => 'OverviewHandler'
  , '|/register$|' => 'RegisterHandler'
  , '|/logout$|' => 'LogoutHandler'
  , '|/login$|' => 'LoginHandler'
  , '|.|' => 'TestappError404Handler'
);
