<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;


// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};
global $post;
$heading = get_field('vf_social_heading');
$color = get_field('vf_social_color');
$outline = get_field('vf_social_outline');
$choose = get_field('vf_social_choose');
$title = get_the_title($post->ID);
$twitter_link = get_field('vf_social_twitter');
$bluesky_link = get_field('vf_social_bluesky');
$facebook_link = get_field('vf_social_facebook');
$instagram_link = get_field('vf_social_instagram');
$youtube_link = get_field('vf_social_youtube');
$linkedin_link = get_field('vf_social_linkedin');
$social_url = get_the_permalink();
$select = get_field('vf_social_select');
$custom = get_field('vf_social_custom');
$custom_url = get_field('vf_social_share_url');
$custom_title = get_field('vf_social_share_title');


$class = '';
if ($outline == 1) {
$class = 'vf-social-links--outline';
}
$dark_mode = '';
if ($color == 'white') {
$dark_mode = 'dark-mode';
}



if (empty($choose)) {
  $choose == 'follow';
}
?>

<svg aria-hidden="true" display="none" class="vf-icon-collection vf-icon-collection--social">
  <defs>
    <g id="vf-social--linkedin">
      <rect xmlns="http://www.w3.org/2000/svg" width="5" height="14" x="2" y="8.5" rx=".5" ry=".5" />
      <ellipse xmlns="http://www.w3.org/2000/svg" cx="4.48" cy="4" rx="2.48" ry="2.5" />
      <path xmlns="http://www.w3.org/2000/svg"
        d="M18.5,22.5h3A.5.5,0,0,0,22,22V13.6C22,9.83,19.87,8,16.89,8a4.21,4.21,0,0,0-3.17,1.27A.41.41,0,0,1,13,9a.5.5,0,0,0-.5-.5h-3A.5.5,0,0,0,9,9V22a.5.5,0,0,0,.5.5h3A.5.5,0,0,0,13,22V14.5a2.5,2.5,0,0,1,5,0V22A.5.5,0,0,0,18.5,22.5Z" />
    </g>
    <g id="vf-social--facebook">
      <path xmlns="http://www.w3.org/2000/svg"
        d="m18.14 7.17a.5.5 0 0 0 -.37-.17h-3.77v-1.41c0-.28.06-.6.51-.6h3a.44.44 0 0 0 .35-.15.5.5 0 0 0 .14-.34v-4a.5.5 0 0 0 -.5-.5h-4.33c-4.8 0-5.17 4.1-5.17 5.35v1.65h-2.5a.5.5 0 0 0 -.5.5v4a.5.5 0 0 0 .5.5h2.5v11.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-11.5h3.35a.5.5 0 0 0 .5-.45l.42-4a.5.5 0 0 0 -.13-.38z" />
    </g>
    <g id="vf-social--twitter">
    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
    </g>
    <g id="vf-social--youtube">
      <path xmlns="http://www.w3.org/2000/svg"
        d="M20.06,3.5H3.94A3.94,3.94,0,0,0,0,7.44v9.12A3.94,3.94,0,0,0,3.94,20.5H20.06A3.94,3.94,0,0,0,24,16.56V7.44A3.94,3.94,0,0,0,20.06,3.5ZM16.54,12,9.77,16.36A.5.5,0,0,1,9,15.94V7.28a.5.5,0,0,1,.77-.42l6.77,4.33a.5.5,0,0,1,0,.84Z" />
    </g>
    <g id="vf-social--instagram">
      <path xmlns="http://www.w3.org/2000/svg"
        d="M17.5,0H6.5A6.51,6.51,0,0,0,0,6.5v11A6.51,6.51,0,0,0,6.5,24h11A6.51,6.51,0,0,0,24,17.5V6.5A6.51,6.51,0,0,0,17.5,0ZM12,17.5A5.5,5.5,0,1,1,17.5,12,5.5,5.5,0,0,1,12,17.5Zm6.5-11A1.5,1.5,0,1,1,20,5,1.5,1.5,0,0,1,18.5,6.5Z" />
    </g>
    <g id="vf-social--bluesky">
      <path xmlns="http://www.w3.org/2000/svg" d="M12 10.8c-1.087 -2.114 -4.046 -6.053 -6.798 -7.995C2.566 0.944 1.561 1.266 0.902 1.565 0.139 1.908 0 3.08 0 3.768c0 0.69 0.378 5.65 0.624 6.479 0.815 2.736 3.713 3.66 6.383 3.364 0.136 -0.02 0.275 -0.039 0.415 -0.056 -0.138 0.022 -0.276 0.04 -0.415 0.056 -3.912 0.58 -7.387 2.005 -2.83 7.078 5.013 5.19 6.87 -1.113 7.823 -4.308 0.953 3.195 2.05 9.271 7.733 4.308 4.267 -4.308 1.172 -6.498 -2.74 -7.078a8.741 8.741 0 0 1 -0.415 -0.056c0.14 0.017 0.279 0.036 0.415 0.056 2.67 0.297 5.568 -0.628 6.383 -3.364 0.246 -0.828 0.624 -5.79 0.624 -6.478 0 -0.69 -0.139 -1.861 -0.902 -2.206 -0.659 -0.298 -1.664 -0.62 -4.3 1.24C16.046 4.748 13.087 8.687 12 10.8"></path>
    </g>
  </defs>
</svg>
<div class="vf-social-links <?php echo $class;?> <?php echo $dark_mode;?>
">
  <?php if (! empty($heading)) { ?>
  <h3 class="vf-social-links__heading">
    <?php echo $heading;  ?>
  </h3>
  <?php } 
  if ($choose == 'follow') {
  
  ?>
  <ul class="vf-social-links__list">
  <?php if (!empty($linkedin_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank" href="<?php echo esc_url($linkedin_link);?>" aria-label="Follow us on Linkedin">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--linkedin">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
    <?php if (!empty($bluesky_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank"  href="<?php echo esc_url($bluesky_link);?>" aria-label="Follow us on Bluesky">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--bluesky" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--bluesky">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
    <?php if (!empty($twitter_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank"  href="<?php echo esc_url($twitter_link);?>" aria-label="Follow us on Twitter">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--twitter">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
    <?php if (!empty($youtube_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank" href="<?php echo esc_url($youtube_link);?>" aria-label="Follow us on Youtube">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--youtube" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--youtube">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
    <?php if (!empty($facebook_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank" href="<?php echo esc_url($facebook_link);?>" aria-label="Follow us on Facebook">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--facebook">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
    <?php if (!empty($instagram_link)) { ?>
    <li class="vf-social-links__item">
      <a class="vf-social-links__link" target="_blank" href="<?php echo esc_url($instagram_link);?>" aria-label="Follow us on Instagram">
        <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--instagram" width="24" height="24"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--instagram">
          </use>
        </svg>
      </a>
    </li>
    <?php } ?>
  </ul>
  <?php } 
  
  else if ($choose == 'share') { ?>
  <ul class="vf-social-links__list">
  <?php if (($select && in_array('linkedin', $select)) || (!empty($linkedin_link))) { ?>
      <li class="vf-social-links__item">
            <a class="vf-social-links__link" target="_blank" aria-label="Share on Linkedin" 
            <?php if ($custom == 'auto') { ?> 
            href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_url; ?>&title=<?php echo $title; ?>">
            <?php } else if ($custom == 'custom') { ?> 
              href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $custom_url; ?><?php if (!empty($custom_title)) { echo '&title=' . $custom_title; } else echo '';?>">
            <?php } ?>  
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--linkedin">
                </use>
              </svg>
            </a>
          </li>
    <?php } ?>
    <?php if (($select && in_array('bluesky', $select)) || (!empty($bluesky_link))) { ?>
      <li class="vf-social-links__item">
            <a class="vf-social-links__link" target="_blank" aria-label="Share on Bluesky"
            <?php if ($custom == 'auto') { ?>
            href="https://bsky.app/intent/compose?text=<?php echo $title; ?>%20<?php echo $social_url; ?>">
            <?php } else if ($custom == 'custom') { ?> 
              href="https://bsky.app/intent/compose?text=<?php if (!empty($custom_title)) { echo $custom_title; } else echo ''; ?>%20<?php echo $custom_url; ?>">
            <?php } ?>  
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--bluesky" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--bluesky">
                </use>
              </svg>
            </a>
          </li>
    <?php } ?>
    <?php if (($select && in_array('twitter', $select)) || (!empty($twitter_link))) { ?>
      <li class="vf-social-links__item">
            <a class="vf-social-links__link" target="_blank" aria-label="Share on Twitter"
            <?php if ($custom == 'auto') { ?>
            href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $social_url; ?>&amp;via=embl">
            <?php } else if ($custom == 'custom') { ?> 
              href="https://twitter.com/intent/tweet?url=<?php echo $custom_url; ?>&amp;via=embl<?php if (!empty($custom_title)) { echo '&text=' . $custom_title; } else echo ''; ?>">
            <?php } ?>  
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--twitter">
                </use>
              </svg>
            </a>
          </li>
    <?php } ?>
    <?php if (($select && in_array('facebook', $select)) || (!empty($facebook_link))) { ?>
      <li class="vf-social-links__item">
            <a class="vf-social-links__link" target="_blank" aria-label="Share on Facebook"
            <?php if ($custom == 'auto') { ?> 
            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_url; ?>">
            <?php } else if ($custom == 'custom') { ?> 
              href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $custom_url; ?>">
            <?php } ?>  
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--facebook">
                </use>
              </svg>
            </a>
          </li>
    <?php } ?>
  </ul>

  <?php } ?>
</div>
