/**
 * URLControl (component)
 * Wrapper for `URLInput`
 */
import React from 'react';
import {URLInput} from '@wordpress/block-editor';
import {BaseControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';

const URLControl = props => {
  return (
    <BaseControl label={props.label}>
      <URLInput
        value={props.value}
        onChange={props.onChange}
        autoFocus={false}
      />
    </BaseControl>
  );
};

export default URLControl;
