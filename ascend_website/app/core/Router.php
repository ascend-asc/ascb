<?php
class Router {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Admin Routes
        if (isset($url[0]) && $url[0] == 'admin') {
            require_once '../admin/index.php';
            exit; // Admin handles its own routing
        }

        // Public Routes
        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }

        // Require the controller
        // Note: For this simplified version we'll assume a flat structure or specific controllers
        // In a full MVC we would have a Controller base class
        // Let's implement a simpler direct routing approach for now if controllers don't exist yet
    }

    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return ['pages', 'index'];
    }
}
