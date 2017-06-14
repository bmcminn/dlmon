<?php

    $files = scandir(getcwd().'/content/audio');

    array_shift($files);
    array_shift($files);
    print_r($files);
