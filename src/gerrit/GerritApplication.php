<?php

final class GerritApplication extends PhabricatorApplication {
  protected static $routes;
  public function getName() {
    return pht('Gerrit');
  }

  public function getBaseURI() {
    return '/r/';
  }

  public function canUninstall() {
    return false;
  }

  public function isUnlisted() {
    return true;
  }

  public function getRoutes() {
    if (isset(self::$routes) && count(self::$routes) > 0) {
      return self::$routes;
    }
/*
The following routes correspond the following gerrit [gitweb] config:

[gitweb]
    url = https://phabricator.wikimedia.org
    type = custom
    revision = "/r/revision/${project};${commit}"
    project = /r/project/${project}
    branch = "/r/branch/${project};${branch}"
    tag = "/r/tag/${project};${tag}"
    filehistory = "/r/browse/${project};${branch};${file}"
    linkname = diffusion
    linkDrafts = false
    urlEncode = false
*/

    $routes = array(
      '/r/(?P<action>p)/(?P<gerritProject>[^;]+);(?P<diffusionArgs>.*)',
      // filehistory
      '/r/(?P<action>[a-z]+)/(?P<gerritProject>[^;]+);(?P<branch>[^;]+);(?P<file>[^;]+)',
      // branch or tag
      '/r/(?P<action>[a-z]+)/(?P<gerritProject>[^;]+);(?P<branch>[^;]+)',
      // commit
      '/r/(?P<action>[a-z]+)/(?P<gerritProject>[^;]+);(?P<sha>[0-9a-z]+)',
      // project
      '/r/(?P<action>[a-z]+)/(?:(?P<gerritProject>[^;]+)/)',
    );

    self::$routes = array_fill_keys($routes, 'GerritProjectController');
    self::$routes['/r/'] = 'GerritProjectListController';
    return self::$routes;
  }

}
