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
        const {name, type} = field;
        attrs[name] = {type: 'string'};
        if (type === 'range') {
          attrs[name]['type'] = 'integer';
        }
        if (type === 'checkbox') {
          attrs[name]['type'] = 'array';
        }
        if (type === 'toggle') {
          attrs[name]['type'] = 'integer';
        }
      });
    }
  }
  return {fields, attrs};
};

export default useVFPlugin;
