<?php

function autoload(string $classname): void
{
    include_once(__DIR__ . '/' . $classname . '.php');
}

spl_autoload_register('autoload');

function printMessage(string $message, array $messageParamaters = []): void
{
    echo strtr($message."\n", $messageParamaters);
}