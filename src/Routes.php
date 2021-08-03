<?php


namespace src;


class Routes
{
    public static array $routes = [
      "retention-graph" => ["GET","HomeController","getRetentionGraphData"]
    ];
}