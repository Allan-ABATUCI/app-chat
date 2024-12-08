<?php
class Controller_list extends Controller
{
    function action_default(): void
    {
        $this->action_contact();
    }
    function action_contact()
    {

        $this->render("contact");
    }

}