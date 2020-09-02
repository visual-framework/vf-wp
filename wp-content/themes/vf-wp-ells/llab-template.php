<?php
/**
* Template Name: LLABs template
*/
get_header();

?>

<style>
  .vf-masthead {
    --vf-masthead__bg-image: url(https://wwwdev.embl.org/ells/wp-content/uploads/2020/08/Screenshot-2020-08-10-at-13.25.31.png);
    --global-theme-fg-color: #fff;
    --global-theme-bg-color: #007a53;
  }

  .vf-masthead--with-title-block .vf-masthead__title:before {
    --global-theme-bg-color: #007a53;
  }

</style>
<div class="vf-masthead vf-masthead--with-title-block | vf-u-margin__bottom--xxl
" style="background-image: url(https://wwwdev.embl.org/ells/wp-content/uploads/2020/08/Screenshot-2020-08-10-at-13.25.31.png);
         --local-theme-fg-color:#FFFFFF;" data-vf-js-masthead>
  <div class="vf-masthead__inner">
    <div class="vf-masthead__title">
      <h1 class="vf-masthead__heading">
        <a href="JavaScript:Void(0);" class="vf-masthead__heading__link">
          Name of LLAB
        </a>
      </h1>
      <h2 class="vf-masthead__subheading">
        <span class="vf-masthead__location">
          Type of LLAB
        </span>
      </h2>
    </div>
  </div>
</div>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
      <h3 class="vf-text vf-text-heading--3 | vf-u-margin__bottom--xl">
      This is an example page. It’s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors.
      </h3>
    <?php 
        the_content();
      ?>
  </div>
  <div>
  <figure class="vf-figure">
<img class="vf-figure__image" src="http://emblebivfwp.docker.localhost:32777/wp-content/uploads/2020/09/LabMatters_1000x600px.jpg" alt="hello alt text">
</figure>
  <div>
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">  <span class="vf-badge vf-badge--secondary">
  Application deadline:</span>  29 Sept 2021</p>
  <hr class="vf-divider">
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Dates:<span class="vf-u-text-color--grey"> 29 - 31 Sept 2021</span></p>
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Contact:<span class="vf-u-text-color--grey"> jane@doe.com</span></p>
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Application with / without selection</p>
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Number of spots in the LLAB:<span class="vf-u-text-color--grey"> 29</span></p>
  <hr class="vf-divider">
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Organisers:<span class="vf-u-text-color--grey"> Jane Doe</span></p>
  <hr class="vf-divider">
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Share:</p>
  <svg aria-hidden="true" display="none" class="vf-icon-collection vf-icon-collection--social">
<defs>
<g id="vf-social--linkedin">
<rect xmlns="http://www.w3.org/2000/svg" width="5" height="14" x="2" y="8.5" rx=".5" ry=".5" />
<ellipse xmlns="http://www.w3.org/2000/svg" cx="4.48" cy="4" rx="2.48" ry="2.5" />
<path xmlns="http://www.w3.org/2000/svg" d="M18.5,22.5h3A.5.5,0,0,0,22,22V13.6C22,9.83,19.87,8,16.89,8a4.21,4.21,0,0,0-3.17,1.27A.41.41,0,0,1,13,9a.5.5,0,0,0-.5-.5h-3A.5.5,0,0,0,9,9V22a.5.5,0,0,0,.5.5h3A.5.5,0,0,0,13,22V14.5a2.5,2.5,0,0,1,5,0V22A.5.5,0,0,0,18.5,22.5Z" />
</g>
<g id="vf-social--facebook">
<path xmlns="http://www.w3.org/2000/svg" d="m18.14 7.17a.5.5 0 0 0 -.37-.17h-3.77v-1.41c0-.28.06-.6.51-.6h3a.44.44 0 0 0 .35-.15.5.5 0 0 0 .14-.34v-4a.5.5 0 0 0 -.5-.5h-4.33c-4.8 0-5.17 4.1-5.17 5.35v1.65h-2.5a.5.5 0 0 0 -.5.5v4a.5.5 0 0 0 .5.5h2.5v11.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-11.5h3.35a.5.5 0 0 0 .5-.45l.42-4a.5.5 0 0 0 -.13-.38z" />
</g>
<g id="vf-social--twitter">
<path xmlns="http://www.w3.org/2000/svg" d="M23.32,6.44a.5.5,0,0,0-.2-.87l-.79-.2A.5.5,0,0,1,22,4.67l.44-.89a.5.5,0,0,0-.58-.7l-2,.56a.5.5,0,0,1-.44-.08,5,5,0,0,0-3-1,5,5,0,0,0-5,5v.36a.25.25,0,0,1-.22.25c-2.81.33-5.5-1.1-8.4-4.44a.51.51,0,0,0-.51-.15A.5.5,0,0,0,2,4a7.58,7.58,0,0,0,.46,4.92.25.25,0,0,1-.26.36L1.08,9.06a.5.5,0,0,0-.57.59,5.15,5.15,0,0,0,2.37,3.78.25.25,0,0,1,0,.45l-.53.21a.5.5,0,0,0-.26.69,4.36,4.36,0,0,0,3.2,2.48.25.25,0,0,1,0,.47A10.94,10.94,0,0,1,1,18.56a.5.5,0,0,0-.2,1,20.06,20.06,0,0,0,8.14,1.93,12.58,12.58,0,0,0,7-2A12.5,12.5,0,0,0,21.5,9.06V8.19a.5.5,0,0,1,.18-.38Z" />
</g>
<g id="vf-social--youtube">
<path xmlns="http://www.w3.org/2000/svg" d="M20.06,3.5H3.94A3.94,3.94,0,0,0,0,7.44v9.12A3.94,3.94,0,0,0,3.94,20.5H20.06A3.94,3.94,0,0,0,24,16.56V7.44A3.94,3.94,0,0,0,20.06,3.5ZM16.54,12,9.77,16.36A.5.5,0,0,1,9,15.94V7.28a.5.5,0,0,1,.77-.42l6.77,4.33a.5.5,0,0,1,0,.84Z" />
</g>
<g id="vf-social--instagram">
<path xmlns="http://www.w3.org/2000/svg" d="M17.5,0H6.5A6.51,6.51,0,0,0,0,6.5v11A6.51,6.51,0,0,0,6.5,24h11A6.51,6.51,0,0,0,24,17.5V6.5A6.51,6.51,0,0,0,17.5,0ZM12,17.5A5.5,5.5,0,1,1,17.5,12,5.5,5.5,0,0,1,12,17.5Zm6.5-11A1.5,1.5,0,1,1,20,5,1.5,1.5,0,0,1,18.5,6.5Z" />
</g>
</defs>
</svg>
<div class="vf-social-links">
<ul class="vf-social-links__list">
<li class="vf-social-links__item">
<a class="vf-social-links__link" href="JavaScript:Void(0);">
<span class="vf-u-sr-only">
twitter
</span>
<svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
<use xlink:href="#vf-social--twitter">
</use>
</svg>
</a>
</li>
<li class="vf-social-links__item">
<a class="vf-social-links__link" href="JavaScript:Void(0);">
<span class="vf-u-sr-only">
facebook
</span>
<svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
<use xlink:href="#vf-social--facebook">
</use>
</svg>
</a>
</li>
<li class="vf-social-links__item">
<a class="vf-social-links__link" href="JavaScript:Void(0);">
<span class="vf-u-sr-only">
instagram
</span>
<svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--instagram" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
<use xlink:href="#vf-social--instagram">
</use>
</svg>
</a>
</li>
</ul>
</div>
  <hr class="vf-divider">
  <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Download</p>

  </div>
  </div>
</section>
