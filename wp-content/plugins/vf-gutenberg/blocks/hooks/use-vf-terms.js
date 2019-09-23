/**
 * Fetch an array of terms for a WP taxonomy asynchronously.
 * Cache in store to only fetch once per page load.
 */
import {useEffect, useState} from 'react';
import useVFGutenberg from './use-vf-gutenberg';
import {useHashsum} from './';

const taxonomyStore = {};

const useVFTerms = taxonomy => {
  const [terms, setTerms] = useState([]);

  const fetchData = async () => {
    if (taxonomyStore.hasOwnProperty(taxonomy)) {
      setTerms(taxonomyStore[taxonomy]);
      return;
    }
    const {postId, nonce} = useVFGutenberg();
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_terms', {
        taxonomy,
        postId,
        nonce
      });
      if (data && data.hasOwnProperty('terms')) {
        taxonomyStore[taxonomy] = data.terms;
        setTerms(data.terms);
      }
    } catch (err) {}
  };

  useEffect(() => {
    fetchData();
  }, [taxonomy]);

  return terms;
};

export default useVFTerms;
