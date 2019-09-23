/**
 * TaxonomyControl (component)
 * Wrapper for `SelectControl` using API taxonomy terms
 */
import React from 'react';
import {SelectControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import useVFTerms from '../hooks/use-vf-terms';

const TaxonomyControl = props => {
  let options = [{label: __('Loading…'), value: props.value}];

  // Set terms once loaded
  const terms = useVFTerms(props.taxonomy);
  if (terms.length) {
    options = terms.map(term => ({
      label: term.name,
      value: parseInt(term.term_id)
    }));
    options.unshift({label: __('Select…'), value: ''});
  }

  // Reset to default value if no term ID is selected
  const onChange = value => {
    const intValue = parseInt(value);
    value = isNaN(intValue) ? '' : intValue;
    props.onChange(value);
  };
  return <SelectControl {...props} onChange={onChange} options={options} />;
};

export default TaxonomyControl;
