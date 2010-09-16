<?php

/*
 * Get a dmGoogleMapTag instance
 */
function _map()
{
  return sfContext::getInstance()->get('google_map_helper')->map();
}

/*
 * Alternative to £map
 */
function £map()
{
  return _map();
}