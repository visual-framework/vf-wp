/**
 * Return block attributes for a VF Plugin (mapped from ACF field object)
 */
import useVFGutenberg from './use-vf-gutenberg';

const useVFPlugin = name => {
  const {plugins} = useVFGutenberg();
  let fields = [];
  let supports = {};
  let attributes = {};
  if (Object.keys(plugins).indexOf(name) > -1) {
    const config = plugins[name];
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
  }
  return {attributes, fields, supports};
};

export default useVFPlugin;
