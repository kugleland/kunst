document.addEventListener('DOMContentLoaded', () => {
    const modelViewer = document.querySelector('#artViewer');
    if (!modelViewer) return;

    // Listen for model load event
    modelViewer.addEventListener('load', () => {
        const imageUrl = modelViewer.getAttribute('data-texture-url');
        if (imageUrl) {
            try {
                // Get the material element
                const materialElement = modelViewer.querySelector('[slot="material"]');
                if (!materialElement) return;

                // Update material properties
                const baseColorTexture = materialElement.querySelector('.base-color-texture');
                if (baseColorTexture) {
                    baseColorTexture.setAttribute('data-uri', imageUrl);
                }

                // Force model-viewer to update
                modelViewer.dispatchEvent(new Event('material-update'));

                console.log('Material updated', modelViewer.model.materials[0]);

                // const texture = modelViewer.createTexture(imageUrl);

                // modelViewer.model.materials[0].setTexture('baseColorTexture', texture);

            } catch (error) {
                console.error('Error applying texture:', error);
            }
        }
    });
}); 