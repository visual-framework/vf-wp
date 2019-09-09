import React, {useState, useEffect} from 'react';

/**
 * Hook to use global VF Gutenberg settings from `wp_localize_script`
 */
const useVF = () => {
  const vf = window.vfGutenberg || {};
  const postId = vf.hasOwnProperty('postId') ? vf.postId : 0;
  const nonce = vf.hasOwnProperty('nonce') ? vf.nonce : '';
  return {
    postId,
    nonce
  };
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
const useVFBlock = attr => {
  const {postId, nonce} = useVF();
  const [data, setData] = useState({});
  const [isLoading, setLoading] = useState(true);

  const fetchData = async () => {
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf_gutenberg_fetch_block', {
        ...attr,
        postId,
        nonce
      });
      setData(data);
      setLoading(false);
      console.log(data);
    } catch (err) {}
  };

  useEffect(() => {
    fetchData();
  }, [...Object.values(attr)]);

  return {data, isLoading};
};

export {useVF, useVFBlock};
