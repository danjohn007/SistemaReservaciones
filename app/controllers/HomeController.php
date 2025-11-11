<?php
/**
 * Controlador de Inicio
 */

class HomeController extends BaseController {
    
    public function index() {
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }
        
        $this->view('home/index');
    }
}
