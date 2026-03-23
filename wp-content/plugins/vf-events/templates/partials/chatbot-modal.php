<?php

$chatbot_markup = VF_Events::get_chatbot_markup();

if ($chatbot_markup === '') {
  return;
}

echo $chatbot_markup;
