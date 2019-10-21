/**
 * Return the server-side rendered template for a VF Gutenberg block
 */
import useVFGutenberg from './use-vf-gutenberg';

const useVFRenderPlugin = async (name, attrs) => {
  try {
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
