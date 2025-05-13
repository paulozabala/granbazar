<?php

    //init session  
    session_start();

    //destroy session variables
    session_unset();


    //destroy session
    session_destroy();


    //redirect to index page
    header("Location: index.html");
    exit();

?>