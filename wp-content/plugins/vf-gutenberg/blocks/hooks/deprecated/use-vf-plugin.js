/**
 * DEPRECATED
 * Return block attributes for a VF Plugin (mapped from ACF field object)
 */
import useVFGutenberg from '../use-vf-gutenberg';

const useVFPlugin = name => {
  const {deprecatedPlugins} = useVFGutenberg();
  let fields = [];
  let supports = {};
  let attributes = {};
  let preview = false;
  if (Object.keys(deprecatedPlugins).indexOf(name) > -1) {
    const config = deprecatedPlugins[name];
    if (config.hasOwnProperty('attributes')) {
      attributes = {...config.attributes};
    }
    if (config.hasOwnProperty('fields')) {
      fields = config.fields;
      fields.forEach(field => {
        attributes[field.name] = {type: field.type, default: field.default};
      });
    }
    if (config.hasOwnProperty('supports')) {
      supports = {...config.supports};
    }
    if (config.hasOwnProperty('preview')) {
      if (/^http/.test(config.preview)) {
        preview = config.preview;
      }
    }
  }
  return {attributes, fields, supports, preview};
};

export default useVFPlugin;
