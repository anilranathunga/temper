<?php

namespace src\configs;

class Config
{
    //deployment related configs
    const APP_URL = "http://localhost:8888/";     // remove trailing slash

    //business logic configs
    const TAX_RATE = 21;

    //application configs
    const DATE_FORMAT = "Y-m-d";
    const CONTROLLERS_PATH ='src\controllers\\';
}