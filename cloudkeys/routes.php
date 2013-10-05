<?php

$routes = array(
    '|/$|' => 'OverviewHandler'
  , '|/ajax$|' => 'AjaxHandler'
  , '|/overview$|' => 'OverviewHandler'
  , '|/register$|' => 'RegisterHandler'
  , '|/logout$|' => 'LogoutHandler'
  , '|/login$|' => 'LoginHandler'
  , '|.|' => 'TestappError404Handler'
);
