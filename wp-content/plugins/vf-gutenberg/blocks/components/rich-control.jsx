/**
 * RichControl (component)
 * Wrapper for `RichText`
 */
import React from 'react';
import {RichText} from '@wordpress/block-editor';
import {BaseControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';

const RichControl = props => {
  return (
    <BaseControl label={props.label}>
      <div className="components-base-control__rich-text">
        <RichText
          tagName={props.tag}
          value={props.value}
          placeholder={props.placeholder}
          onChange={props.onChange}
        />
      </div>
    </BaseControl>
  );
};

export default RichControl;
