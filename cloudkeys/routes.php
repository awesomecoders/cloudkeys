<?php

$routes = array(
    '|/$|' => 'OverviewHandler'
  , '|/ajax$|' => 'AjaxHandler'
  , '|/overview$|' => 'OverviewHandler'
  , '|/register$|' => 'RegisterHandler'
  , '|/login$|' => 'LoginHandler'
  , '|.|' => 'TestappError404Handler'
);
