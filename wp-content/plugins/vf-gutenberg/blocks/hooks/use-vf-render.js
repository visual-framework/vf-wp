/**
 * Render the template for a VF Gutenberg block asynchronously
 */
import {useEffect, useState} from 'react';
import {useHashsum} from './';
import useNunjucks from './use-nunjucks';
// import useVFRenderPlugin from './deprecated/use-vf-render-plugin';
// import useVFRenderTemplate from './use-vf-render-template';

const useVFRenderTemplate = (name, attrs) => {
  try {
    const nunjucks = useNunjucks();
    const html = nunjucks.render(name.replace(/^vf\//, 'vf-'), attrs);
    return {
      html: html,
      hash: useHashsum(html)
    };
  } catch (err) {
    console.log(err);
    return null;
  }
};

const renderStore = {};

const useVFRender = props => {
  const [data, setData] = useState(null);
  const [isLoading, setLoading] = useState(false);
  if (props.isRenderable === false) {
    return [data, false];
  }

  const hasTemplate = 'render' in props.attributes;

  // extract attributes and remove protected
  const renderAttrs = {...props.attributes, ...(props.transient || {})};
  delete renderAttrs['ver'];
  delete renderAttrs['mode'];
  delete renderAttrs['render'];

  const renderHash = useHashsum([props.name, renderAttrs]);

  const fetchData = async () => {
    if (renderStore.hasOwnProperty(renderHash)) {
      setData(renderStore[renderHash]);
      return;
    }
    let newData = null;
    if (hasTemplate) {
      newData = useVFRenderTemplate(props.name, renderAttrs);
    } else {
      // Deprecated
      return;
      // if (props.isEditing) {
      //   return;
      // }
      // setLoading(true);
      // newData = await useVFRenderPlugin(props.name, renderAttrs);
      // setLoading(false);
    }
    renderStore[renderHash] = newData;
    setData(newData);
  };

  // provide attributes hash to avoid rerenders
  useEffect(() => {
    fetchData();
  }, [renderHash, props.isEditing]);

  if (data && hasTemplate) {
    props.setAttributes({render: data.html});
  }

  return [data, isLoading];
};

export default useVFRender;
