/**
Block Name: Cluster
*/
import React, { useCallback, useEffect } from "react";
import { createBlock } from "@wordpress/blocks";
import { InnerBlocks, InspectorControls } from "@wordpress/block-editor";
import { Button, PanelBody } from "@wordpress/components";
import { useDispatch, useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import useVFDefaults from "../hooks/use-vf-defaults";
import VFBlockFields from "../vf-block/block-fields";

const defaults = useVFDefaults();

const ver = "1.0.0";

const settings = {
  ...defaults,
  name: "vf/cluster",
  title: __("VF Cluster"),
  category: "vf/core",
  description: __("Visual Framework (core)"),
  attributes: {
    ...defaults.attributes
  }
};

settings.save = props => {
  return (
    <div className="vf-cluster">
      <div className="vf-cluster__inner">
        <InnerBlocks.Content />
      </div>
    </div>
  );
};

settings.edit = props => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ ver });
  }

  const { clientId } = props;

  // Inspector controls
  const fields = [];

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Settings")} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className="vf-cluster" data-ver={ver}>
        <div className="vf-cluster__inner">
          <InnerBlocks />
        </div>
      </div>
    </>
  );
};

export default settings;
