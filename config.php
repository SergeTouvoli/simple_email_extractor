<?php

define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
define('BASE_DIR', realpath(dirname(__FILE__) . "/../"));

const EMAIL_DOMAINS = [
    "@gmail.com",
    "@outlook.com",
    "@yahoo.com",
];

const PARAM_INT = 'int';
const PARAM_STR = 'string';
const PARAM_BOOL = 'bool';
const PARAM_ARRAY = 'array';
const PARAM_FLOAT = 'float';


const HOME_PAGE = "accueil";

const API = "api";
const GENERATE_TEXT = "generate-text";
const EXTRACT_EMAIL = "extract-email";
