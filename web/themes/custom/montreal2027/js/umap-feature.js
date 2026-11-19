/**
 * @file
 * Sets the `feature` query parameter of an embedded umap iframe when a
 * `<details data-feature>` element is opened.
 */
((Drupal, once) => {
  const UMAP_HOST = 'umap.osm.ch';

  Drupal.behaviors.m2027UmapFeature = {
    attach(context) {
      const items = once('m2027-umap-feature', 'details[data-feature]', context);
      if (!items.length) {
        return;
      }

      // Ignore toggle events fired for details that are open on page load.
      let ready = false;
      requestAnimationFrame(() => {
        ready = true;
      });

      items.forEach((details) => {
        details.addEventListener('toggle', () => {
          const feature = details.dataset.feature;
          if (!ready || !details.open || !feature) {
            return;
          }

          const scope = details.closest('.node') || document;
          const iframe = scope.querySelector(`iframe[src*="${UMAP_HOST}"]`);
          if (!iframe) {
            return;
          }

          let url;
          try {
            url = new URL(iframe.src);
          } catch (e) {
            return;
          }
          if (url.hostname !== UMAP_HOST) {
            return;
          }

          // Value format: "<name>[#zoom/lat/lng]"; name may be pre-encoded.
          const hashIndex = feature.indexOf('#');
          const rawName = hashIndex === -1 ? feature : feature.slice(0, hashIndex);
          let name = rawName;
          try {
            name = decodeURIComponent(rawName);
          } catch (e) {
            // Keep the raw name if it is not valid percent-encoding.
          }

          url.searchParams.set('feature', name);
          // URLSearchParams encodes spaces as "+"; use %20 instead.
          url.search = url.search.replace(/\+/g, '%20');
          url.hash = hashIndex === -1 ? '' : feature.slice(hashIndex + 1);
          if (url.toString() !== iframe.src) {
            iframe.src = url.toString();
          }
        });
      });
    },
  };
})(Drupal, once);
