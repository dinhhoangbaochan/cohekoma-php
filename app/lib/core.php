<?php

class Core {
  protected $currentController = 'Pages';
  protected $currentMethod = 'index';
  protected $params = [];

  public function __construct()
  {
    $url = $this->getUrl();
    $controllerName = $url[0];
    $methodName = $url[1];

    if ( file_exists( '../app/controllers/' . ucwords( $controllerName ) . '.php' ) ) {
      $this->currentController = ucwords( $controllerName );
      unset( $url[0] );
    }

    require_once '../app/controllers/' . $this->currentController . '.php';

    $this->currentController = new $this->currentController;

    // Check if URL contains the method - which is second index of the url.
    if ( isset( $methodName ) ) {
      if ( method_exists( $this->currentController, $methodName ) ) {
        $this->currentMethod = $methodName;
        unset( $url[1] );
      }
    }

    $this->params = ( $url ) ? array_values( $url ) : [];

    call_user_func_array( [$this->currentController, $this->currentMethod], $this->params );
  }

  public function getUrl()
  {
    if ( isset( $_GET['url'] ) ) {
      $url = rtrim( $_GET['url'], '/' );
      $url = filter_var( $url, FILTER_SANITIZE_URL );
      $url = explode( '/', $url );

      return $url;
    }
  }
}