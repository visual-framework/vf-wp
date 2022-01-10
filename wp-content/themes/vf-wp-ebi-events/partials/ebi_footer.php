<style>
  #event-information .vf-cluster__inner :first-child {
    --vf-cluster__item--flex: 40% 0 1 !important;
  }

  .custom_font_text_size {
    font-size: 17px;
  }
  .custom_font_text_size_extra {
    font-size: 19px;
  }
  .custom_font_date {
    font-size: 19px;
    color: #1a1c1a;
  }
</style>
<!-- embl-ebi global footer -->
<link rel="import" href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=106902&pattern=node-body&source=contenthub" data-target="self" data-embl-js-content-hub-loader>
<div class="vf-u-display-none" data-protection-message-disable="true"></div>

<script>
  // Hide the paging if there is no results.
  (function() {

    if (/complete|interactive|loaded/.test(document.readyState)) {
      handleReady();
    } else {
      document.addEventListener('DOMContentLoaded', handleReady);
    }

    function handleReady() {
      // Ensure input element exists
      const el = document.querySelector('#totalrecords');
      const pagingCount = el.textContent || el.innerText;
      if (pagingCount <= 0) {
        $('#paging-data').hide();
      }
    } // handleReady

  })();
</script>
