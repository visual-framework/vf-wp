<?php
        $peopleBaseUrl = "https://www.embl.org/internal-information/people/";
        $peopleSlug = basename($person->personUrl);
        $peopleEndUrl = $peopleBaseUrl . $peopleSlug;
        

echo '<p>
        <span><a href="' . $peopleEndUrl . '">' . $person->displayName . '</a></span><span>, ' . $person->title . ', </span> 
        <span>' . $person->orgUnitName . '</span> 
      </p>
'; ?>


