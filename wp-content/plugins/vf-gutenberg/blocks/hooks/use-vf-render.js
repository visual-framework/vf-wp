/**
 * Fetch the rendered template for a VF Gutenberg block asynchronously,
 * returning fetched data and loading state.
 */
import {useEffect, useState} from 'react';
import useVFGutenberg from './use-vf-gutenberg';
import {useHashsum} from './';

const useVFRender = attrs => {
  const [data, setData] = useState(null);
  const [isLoading, setLoading] = useState(false);

  const fetchData = async () => {
    const {postId, nonce} = useVFGutenberg();
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
        ...attrs,
        postId,
        nonce
      });
      setData(data);
      setLoading(false);
    } catch (err) {}
  };

  // provide attributes hash to avoid re-fetching unchanged blocks
  useEffect(() => {
    fetchData();
  }, [useHashsum(attrs)]);

  return [data, isLoading];
};

export default useVFRender;
