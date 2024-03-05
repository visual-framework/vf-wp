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
        </defs>
      </svg>
      <div class="vf-social-links">
        <p class="vf-text-body vf-text-body--3" style="font-weight: 600;">
          Share this event
        </p>
        <ul class="vf-social-links__list">
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $social_url; ?>&amp;via=embl">
              <span class="vf-u-sr-only">
                twitter
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
          <use xlink:href="#vf-social--twitter"></use>
        </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_url; ?>">
              <span class="vf-u-sr-only">
                facebook
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--facebook">
                </use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_url; ?>&title=<?php echo $title; ?>">
              <span class="vf-u-sr-only">
                linkedin
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--linkedin">
                </use>
              </svg>
            </a>
          </li>
        </ul>

        <?php if ( ! empty($hashtag) && $event_organiser == "cco_hd") { ?>
        <p class="vf-text-body vf-text-body--2 vf-u-text-color--grey"> <?php echo esc_html($hashtag); ?> </p>
        <?php } ?>

      </div>
