/**
 * Fetch the rendered template for a VF Gutenberg block asynchronously,
 * returning fetched data. Cache results in store.
 */
import {useEffect, useState} from 'react';
import useVFGutenberg from './use-vf-gutenberg';
import {useHashsum} from './';

const store = {};

const useVFRender = attrs => {
  const [data, setData] = useState(null);
  const hash = useHashsum(attrs);

  const fetchData = async () => {
    // return matching hash from internal store
    if (store.hasOwnProperty(hash)) {
      setData(store[hash]);
      return;
    }
    const {postId, nonce} = useVFGutenberg();
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
        ...attrs,
        postId,
        nonce
      });
      store[hash] = data;
      setData(data);
    } catch (err) {}
  };

  // provide attributes hash to avoid rerenders
  useEffect(() => {
    fetchData();
  }, [hash]);

  return data;
};

export default useVFRender;
