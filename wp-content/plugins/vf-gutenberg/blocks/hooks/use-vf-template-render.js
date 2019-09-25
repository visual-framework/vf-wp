/**
 * Fetch the rendered template for a VF Gutenberg block asynchronously,
 * returning fetched data. Cache results in store.
 */
import {useEffect, useState} from 'react';
import useNunjucks from './use-nunjucks';
import {useHashsum} from './';

const store = {};

const useVFTemplateRender = (name, attrs) => {
  const [data, setData] = useState(null);
  const hash = useHashsum(attrs);
  const nunjucks = useNunjucks();

  const renderTemplate = async () => {
    // return matching hash from internal store
    if (store.hasOwnProperty(hash)) {
      setData(store[hash]);
      return;
    }
    try {
      const data = {
        html: nunjucks.render(name, attrs)
      };
      store[hash] = data;
      setData(data);
    } catch (err) {}
  };

  // provide attributes hash to avoid rerenders
  useEffect(() => {
    renderTemplate();
  }, [hash]);

  return data;
};

export default useVFTemplateRender;
