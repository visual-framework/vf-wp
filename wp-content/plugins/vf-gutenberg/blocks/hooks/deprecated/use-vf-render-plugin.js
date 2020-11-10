/**
 * Return the server-side rendered template for a VF Gutenberg block
 */
import useVFGutenberg from '../use-vf-gutenberg';

const useVFRenderPlugin = async (name, attrs) => {
  try {
    // Return empty HTML if iframe URL is set as transient property
    if (attrs.defaults === 1 && !!attrs.preview) {
      // const data = await fetch(attrs.preview)
      //   .then(response => {
      //     if (response.status !== 200) {
      //       throw new Error();
      //     }
      //     return response.text();
      //   })
      //   .then(html => {
      //     return {
      //       hash: name,
      //       html: html
      //     };
      //   })
      //   .catch(err => {
      //     return {
      //       hash: name,
      //       html: '',
      //       src: attrs.preview
      //     };
      //   });
      // return data;
      return {
        hash: name,
        html: '',
        src: attrs.preview
      };
    }
    // Otherwise fetch block render
    const {postId, nonce} = useVFGutenberg();
    const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
      attrs,
      name,
      postId,
      nonce
    });
    return data;
  } catch (err) {
    console.log(err);
    return null;
  }
};

export default useVFRenderPlugin;
