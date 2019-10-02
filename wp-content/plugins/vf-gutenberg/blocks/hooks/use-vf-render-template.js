/**
 * Return the Nunjucks rendered template for a VF Gutenberg block
 */
import useNunjucks from './use-nunjucks';

const useVFRenderTemplate = (name, attrs) => {
  try {
    const nunjucks = useNunjucks();
    return {
      html: nunjucks.render(name.replace(/^vf\//, 'vf-'), attrs)
    };
  } catch (err) {
    console.log(err);
    return null;
  }
};

export default useVFRenderTemplate;
