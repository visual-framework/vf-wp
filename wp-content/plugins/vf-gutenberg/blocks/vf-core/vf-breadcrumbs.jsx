/**
Block Name: Breadcrumbs
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import {withTransientInnerBlocks} from '../hooks/with-transient';

import '@visual-framework/vf-breadcrumbs/vf-breadcrumbs.precompiled';

const withBreadcrumbsItems = Edit => {
  return props => {
    const transient = {...(props.transient || {})};
    transient.breadcrumbs = [];
    if (Array.isArray(transient.innerBlocks)) {
      transient.innerBlocks.forEach(block => {
        if (block.name === 'vf/breadcrumbs-item') {
          transient.breadcrumbs.push({
            text: block.attributes.text,
            url: block.attributes.url,
            // Nunjucks template changed from `url`?
            breadcrumb_href: block.attributes.url
          });
        }
      });
    }
    return Edit({...props, transient});
  };
};

export default useVFCoreSettings({
  name: 'vf/breadcrumbs',
  title: __('Breadcrumbs'),
  allowedBlocks: ['vf/breadcrumbs-item'],
  withHOC: [[withBreadcrumbsItems], [withTransientInnerBlocks]]
});
