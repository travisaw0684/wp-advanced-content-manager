import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl } from '@wordpress/components';

registerBlockType('acm/resource-grid', {
    edit({ attributes, setAttributes }) {

        return (
            <>
                <InspectorControls>
                    <PanelBody title="Grid Settings">
                        <RangeControl
                            label="Posts Per Page"
                            min={1}
                            max={12}
                            value={attributes.postsPerPage}
                            onChange={(value) => setAttributes({ postsPerPage: value })}
                        />
                        <TextControl
                            label="Default Category Slug"
                            value={attributes.defaultCategory}
                            onChange={(value) => setAttributes({ defaultCategory: value })}
                        />
                    </PanelBody>
                </InspectorControls>

                <div className="acm-block-preview">
                    <p><strong>Resource Grid</strong></p>
                    <p>Posts per page: {attributes.postsPerPage}</p>
                    <p>Default category: {attributes.defaultCategory || 'All'}</p>
                    <p><em>Preview loads on frontend.</em></p>
                </div>
            </>
        );
    }
});
