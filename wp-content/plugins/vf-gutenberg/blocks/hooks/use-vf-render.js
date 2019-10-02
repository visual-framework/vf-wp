/**
 * Render the template for a VF Gutenberg block asynchronously
 */
import {useEffect, useState} from 'react';
import {useHashsum} from './';
import useVFRenderPlugin from './use-vf-render-plugin';
import useVFRenderTemplate from './use-vf-render-template';

const renderStore = {};

const useVFRender = (props, {isEditing}) => {
  const [data, setData] = useState(null);
  if (props.hasRender === false) {
    return data;
  }

  const hasTemplate = 'render' in props.attributes;

  // extract attributes and remove protected
  const renderAttrs = {...props.attributes, ...(props.transient || {})};
  delete renderAttrs['ver'];
  delete renderAttrs['mode'];
  delete renderAttrs['render'];

  const renderHash = useHashsum(renderAttrs);

  const fetchData = async () => {
    if (renderStore.hasOwnProperty(renderHash)) {
      setData(renderStore[renderHash]);
      return;
    }
    let newData = null;
    if (hasTemplate) {
      newData = await useVFRenderTemplate(props.name, renderAttrs);
    } else {
      if (isEditing) {
        return;
      }
      newData = await useVFRenderPlugin(props.name, renderAttrs);
    }
    renderStore[renderHash] = newData;
    setData(newData);
  };

  // provide attributes hash to avoid rerenders
  useEffect(() => {
    fetchData();
  }, [renderHash, isEditing]);

  if (data && hasTemplate) {
    props.setAttributes({render: data.html});
  }

  return data;
};

export default useVFRender;

// // Hook in conditional against the rules?
// const data = (() => {
//   if (!isRenderable) {
//     return null;
//   }
//   /**
//    * Include transient properties in the attributes passed to the render
//    * function that will not be saved to the block JSON.
//    */

//   render using Nunjucks template
//   if ('render' in attrs) {
//     const render = useVFRenderTemplate(props.name, renderAttrs);
//     if (!render) {
//       return null;
//     }
//     let html = attrs.render;
//     // update attribute for main block only and not style previews
//     if (props.clientId) {
//       props.setAttributes({render: render.html});
//     } else {
//       html = render.html;
//     }
//     return {html};
//   }
//   // render using server-side plugin
//   return isEditing ? null : useVFRenderPlugin(props.name, renderAttrs);
// })();
