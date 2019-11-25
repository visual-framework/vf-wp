/**
 * URLControl (component)
 * Wrapper for `URLInput`
 */
import React from 'react';
import {URLInput} from '@wordpress/block-editor';
import {BaseControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';

const URLControl = props => {
  let className = '';
  if (!props.disableSuggestions) {
    className += 'has-suggestions';
  }
  return (
    <BaseControl label={props.label}>
      <URLInput
        autoFocus={false}
        className={className}
        disableSuggestions={props.disableSuggestions}
        onChange={props.onChange}
        value={props.value}
      />
    </BaseControl>
  );
};

export default URLControl;
