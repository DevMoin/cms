<?php
namespace App;
class CMS {
    private static $instance; // Singleton instance
    private $q; // Variable to store the value of $q

    // Private constructor to prevent instantiation from outside
    private function __construct($q) {
        $this->q = $_GET['q'];
    }

    // Method to get the singleton instance
    public static function getInstance($q) {
        if (!isset(self::$instance)) {
            self::$instance = new CMS($q);
        }
        return self::$instance;
    }

    // Method to get the value of $q
    public function getQ() {
        return $this->q;
    }
}