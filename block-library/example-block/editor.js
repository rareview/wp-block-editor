import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';

import metadata from './block.json';

registerBlockType( metadata.name, {
	title : metadata.title,

	edit : ( props ) => {
		const blockProps = useBlockProps();
		return (
			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ props.attributes }
				/>
			</div>
		);
	},

	save : () => null,
} );
