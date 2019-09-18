/**
 * Return block attributes for a VF Plugin (mapped from ACF field object)
 */
import useVFGutenberg from './use-vf-gutenberg';

const useVFPlugin = name => {
  const {plugins} = useVFGutenberg();
  let fields = [];
  let attrs = {};
  if (Object.keys(plugins).indexOf(name) > -1) {
    const config = plugins[name];
    if (config.hasOwnProperty('fields')) {
      fields = config.fields;
      fields.forEach(field => {
        attrs[field.name] = {type: field.type, default: field.default};
      });
    }
  }
  return {fields, attrs};
};

export default useVFPlugin;
