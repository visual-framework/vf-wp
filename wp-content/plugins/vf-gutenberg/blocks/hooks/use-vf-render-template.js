/**
 * Return the Nunjucks rendered template for a VF Gutenberg block
 */
import {useHashsum} from './';
import useNunjucks from './use-nunjucks';

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

export default useVFRenderTemplate;
